const express = require('express');
const cors = require('cors');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const fs = require('fs').promises;
const path = require('path');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3001;

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.static('dist'));

// File paths
const USERS_FILE = path.join(__dirname, 'data', 'users.json');
const POSTS_FILE = path.join(__dirname, 'data', 'posts.json');

// Ensure data directory exists
const ensureDataDir = async () => {
  const dataDir = path.join(__dirname, 'data');
  try {
    await fs.access(dataDir);
  } catch {
    await fs.mkdir(dataDir, { recursive: true });
  }
};

// Helper functions
const readJsonFile = async (filePath) => {
  try {
    const data = await fs.readFile(filePath, 'utf8');
    return JSON.parse(data);
  } catch {
    return {};
  }
};

const writeJsonFile = async (filePath, data) => {
  await fs.writeFile(filePath, JSON.stringify(data, null, 2));
};

const readPostsFile = async () => {
  try {
    const data = await fs.readFile(POSTS_FILE, 'utf8');
    return JSON.parse(data);
  } catch {
    return [];
  }
};

const writePostsFile = async (data) => {
  await fs.writeFile(POSTS_FILE, JSON.stringify(data, null, 2));
};

// Auth middleware
const authenticateToken = (req, res, next) => {
  const authHeader = req.headers['authorization'];
  const token = authHeader && authHeader.split(' ')[1];

  if (!token) {
    return res.status(401).json({ error: 'Access token required' });
  }

  jwt.verify(token, process.env.JWT_SECRET, (err, user) => {
    if (err) {
      return res.status(403).json({ error: 'Invalid token' });
    }
    req.user = user;
    next();
  });
};

// Routes

// Sign up
app.post('/api/signup', async (req, res) => {
  try {
    const { email, password } = req.body;
    
    if (!email || !password) {
      return res.status(400).json({ error: 'Email and password required' });
    }

    const users = await readJsonFile(USERS_FILE);
    
    if (users[email]) {
      return res.status(400).json({ error: 'User already exists' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    users[email] = { password: hashedPassword };
    
    await writeJsonFile(USERS_FILE, users);
    
    res.json({ message: 'User created successfully' });
  } catch (error) {
    res.status(500).json({ error: 'Server error' });
  }
});

// Login
app.post('/api/login', async (req, res) => {
  try {
    const { email, password } = req.body;
    
    if (!email || !password) {
      return res.status(400).json({ error: 'Email and password required' });
    }

    const users = await readJsonFile(USERS_FILE);
    const user = users[email];
    
    if (!user || !(await bcrypt.compare(password, user.password))) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    const token = jwt.sign({ email }, process.env.JWT_SECRET, { expiresIn: '24h' });
    
    res.json({ token, email });
  } catch (error) {
    res.status(500).json({ error: 'Server error' });
  }
});

// Get top posts
app.get('/api/posts/top', async (req, res) => {
  try {
    const posts = await readPostsFile();
    const topPosts = posts
      .sort((a, b) => b.likes - a.likes)
      .slice(0, 5);
    
    res.json(topPosts);
  } catch (error) {
    res.status(500).json({ error: 'Server error' });
  }
});

// Get all posts
app.get('/api/posts', authenticateToken, async (req, res) => {
  try {
    const posts = await readPostsFile();
    res.json(posts);
  } catch (error) {
    res.status(500).json({ error: 'Server error' });
  }
});

// Create post
app.post('/api/posts', authenticateToken, async (req, res) => {
  try {
    const { title, content } = req.body;
    
    if (!title) {
      return res.status(400).json({ error: 'Title is required' });
    }

    const posts = await readPostsFile();
    const newPost = {
      id: Date.now().toString(),
      title,
      content: content || '',
      author: req.user.email,
      likes: 0,
      createdAt: new Date().toISOString()
    };
    
    posts.push(newPost);
    await writePostsFile(posts);
    
    res.json(newPost);
  } catch (error) {
    res.status(500).json({ error: 'Server error' });
  }
});

// Like post
app.post('/api/posts/:id/like', authenticateToken, async (req, res) => {
  try {
    const { id } = req.params;
    const posts = await readPostsFile();
    
    const postIndex = posts.findIndex(post => post.id === id);
    if (postIndex === -1) {
      return res.status(404).json({ error: 'Post not found' });
    }
    
    posts[postIndex].likes += 1;
    await writePostsFile(posts);
    
    res.json(posts[postIndex]);
  } catch (error) {
    res.status(500).json({ error: 'Server error' });
  }
});

// Initialize data directory
ensureDataDir().then(() => {
  app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
  });
});