import React from 'react';
import { Link } from 'react-router-dom';
import './Home.css';

const Home = () => {
  const categories = [
    { name: 'Pizza', path: '/pizza', image: 'Pictures/pizza.jpg' },
    { name: 'Burger', path: '/burger', image: 'Pictures/burger.jfif' },
    { name: 'Biryani', path: '/biryani', image: 'Pictures/biryani.jpg' },
    { name: 'Chinese', path: '/chinese', image: 'Pictures/chiness food.jpg' },
    { name: 'Ice Cream', path: '/icecream', image: 'Pictures/ice cream.jpg' },
    { name: 'North Indian', path: '/northindian', image: 'Pictures/north indian.jpg' },
    { name: 'South Indian', path: '/southindian', image: 'Pictures/south indian.jpg' },
    { name: 'Desserts', path: '/desserts', image: 'Pictures/desserts.jpg' },
    { name: 'Beverages', path: '/beverages', image: 'Pictures/Beverages.jpg' },
    { name: 'Fast Food', path: '/fastfood', image: 'Pictures/fast food.jpg' },
  ];

  const restaurants = [
    { name: 'Spice Garden', image: 'Pictures/spice garden.png', rating: '4.2', time: '30-40 mins' },
    { name: 'Pizza Hut', image: 'Pictures/pizza hutt.png', rating: '4.5', time: '25-35 mins' },
    { name: 'Biryani House', image: 'Pictures/biryani-house.jpg', rating: '4.3', time: '20-30 mins' },
    { name: "McDonald's", image: 'Pictures/mcdonald.jpg', rating: '4.1', time: '15-25 mins' },
    { name: 'KFC', image: 'Pictures/kfc.jpg', rating: '4.0', time: '20-30 mins' },
    { name: "Domino's Pizza", image: 'Pictures/domino\'z.jpg', rating: '4.4', time: '25-35 mins' },
  ];

  return (
    <div className="home-page">
      <section className="hero">
        <div className="hero-content">
          <h1>Welcome to TIF Service</h1>
          <input type="text" placeholder="Enter your delivery address" />
          <button>Find Food</button>
        </div>
      </section>

      <section className="categories-section">
        <h2>What's on your mind?</h2>
        <div className="categories-grid">
          {categories.map((category, index) => (
            <Link key={index} to={category.path} className="category-card">
              <img src={category.image} alt={category.name} />
              <p>{category.name}</p>
            </Link>
          ))}
        </div>
      </section>

      <section className="restaurants-section">
        <h2>Top restaurants in your area</h2>
        <div className="restaurants-grid">
          {restaurants.map((restaurant, index) => (
            <div key={index} className="restaurant-card">
              <img src={restaurant.image} alt={restaurant.name} />
              <div className="restaurant-info">
                <h3>{restaurant.name}</h3>
                <p>{restaurant.rating} ⭐ • {restaurant.time}</p>
              </div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
};

export default Home;
