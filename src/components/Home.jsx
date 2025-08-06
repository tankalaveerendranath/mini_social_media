import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import './Home.css';

const Home = () => {
  const [topPosts, setTopPosts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchTopPosts();
  }, []);

  const fetchTopPosts = async () => {
    try {
      const response = await fetch('/api/posts/top');
      const posts = await response.json();
      setTopPosts(posts);
    } catch (error) {
      console.error('Error fetching posts:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="home">
      <header className="header">
        <h1>Welcome to My Social Media</h1>
        <div className="auth-buttons">
          <Link to="/login" className="btn btn-primary">Login</Link>
          <Link to="/signup" className="btn btn-secondary">Sign Up</Link>
        </div>
      </header>

      <main className="main-content">
        <h2>Top 5 Posts</h2>
        
        {loading ? (
          <div className="loading">Loading posts...</div>
        ) : topPosts.length > 0 ? (
          <div className="posts-container">
            {topPosts.map((post) => (
              <div key={post.id} className="post">
                <h3>{post.title}</h3>
                {post.content && <p className="post-content">{post.content}</p>}
                <div className="post-meta">
                  <span className="likes">❤️ {post.likes} likes</span>
                  <span className="author">by {post.author}</span>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="no-posts">
            <p>No posts available yet. Be the first to create one!</p>
            <Link to="/signup" className="btn btn-primary">Get Started</Link>
          </div>
        )}
      </main>
    </div>
  );
};

export default Home;