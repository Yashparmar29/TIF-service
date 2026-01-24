import React from 'react';
import { Link } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';
import { categories } from '../data/categories';
import { restaurants } from '../data/restaurants';

const HomePage = () => {
  return (
    <div>
      <Navbar />
      <div className="bg-gradient-to-r from-orange-400 to-pink-500 h-96 flex items-center justify-center text-white">
        <div className="bg-white bg-opacity-90 p-8 rounded-lg max-w-lg w-full text-center">
          <input
            type="text"
            placeholder="Enter your delivery address"
            className="w-full p-4 mb-4 rounded text-black"
          />
          <button className="bg-red-600 text-white px-8 py-4 rounded font-bold">Find Food</button>
        </div>
      </div>
      <section className="p-8 max-w-6xl mx-auto">
        <h2 className="text-2xl font-bold mb-4">What's on your mind?</h2>
        <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
          {categories.map(category => (
            <Link key={category.id} to={`/category/${category.id}`} className="block">
              <div className="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                <img src={category.image} alt={category.name} className="w-full h-32 object-cover" />
                <p className="p-4 text-center font-semibold">{category.name}</p>
              </div>
            </Link>
          ))}
        </div>
      </section>
      <section className="p-8 max-w-6xl mx-auto">
        <h2 className="text-2xl font-bold mb-4">Top restaurants in your area</h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          {restaurants.map(restaurant => (
            <div key={restaurant.id} className="bg-white rounded-lg overflow-hidden shadow-md">
              <img src={restaurant.image} alt={restaurant.name} className="w-full h-48 object-cover" />
              <div className="p-4">
                <h3 className="font-bold text-lg">{restaurant.name}</h3>
                <p className="text-gray-600">{restaurant.cuisines} • ⭐ {restaurant.rating} • {restaurant.deliveryTime}</p>
              </div>
            </div>
          ))}
        </div>
      </section>
      <Footer />
    </div>
  );
};

export default HomePage;
