import React, { useState, useMemo } from 'react';
import { useParams } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';
import { restaurants } from '../data/restaurants';
import { categories } from '../data/categories';

const CategoryPage = () => {
  const { category } = useParams();
  const categoryName = categories.find(cat => cat.id === category)?.name || 'Category';

  const [sortBy, setSortBy] = useState('rating');
  const [quickDelivery, setQuickDelivery] = useState(false);

  const filteredRestaurants = useMemo(() => {
    let filtered = restaurants.filter(r => r.categories.includes(category));
    if (quickDelivery) {
      filtered = filtered.filter(r => {
        const time = r.deliveryTime.split('-')[0];
        return parseInt(time) < 30;
      });
    }
    filtered.sort((a, b) => {
      if (sortBy === 'rating') return b.rating - a.rating;
      if (sortBy === 'delivery') {
        const aTime = parseInt(a.deliveryTime.split('-')[0]);
        const bTime = parseInt(b.deliveryTime.split('-')[0]);
        return aTime - bTime;
      }
      return 0;
    });
    return filtered;
  }, [category, sortBy, quickDelivery]);

  return (
    <div>
      <Navbar />
      <div className="p-8 max-w-6xl mx-auto">
        <h1 className="text-3xl font-bold mb-8">{categoryName}</h1>
        <div className="flex gap-4 mb-8">
          <select value={sortBy} onChange={e => setSortBy(e.target.value)} className="p-2 border rounded">
            <option value="rating">Sort by Rating</option>
            <option value="delivery">Sort by Delivery Time</option>
          </select>
          <label className="flex items-center gap-2">
            <input type="checkbox" checked={quickDelivery} onChange={e => setQuickDelivery(e.target.checked)} />
            Quick Delivery
          </label>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          {filteredRestaurants.map(restaurant => (
            <div key={restaurant.id} className="bg-white rounded-lg overflow-hidden shadow-md">
              <img src={restaurant.image} alt={restaurant.name} className="w-full h-48 object-cover" />
              <div className="p-4">
                <h3 className="font-bold text-lg">{restaurant.name}</h3>
                <p className="text-gray-600">{restaurant.cuisines} • ⭐ {restaurant.rating} • {restaurant.deliveryTime}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
      <Footer />
    </div>
  );
};

export default CategoryPage;
