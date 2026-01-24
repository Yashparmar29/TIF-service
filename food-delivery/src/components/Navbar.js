import React from 'react';
import { Link } from 'react-router-dom';

const Navbar = () => {
  return (
    <nav className="bg-red-600 text-white p-4 flex justify-between items-center sticky top-0 z-10">
      <div className="text-xl font-bold">TIF Service</div>
      <div className="flex items-center gap-2">
        <span>📍</span>
        <span>Select Location</span>
      </div>
      <div className="flex-1 max-w-md mx-8">
        <input
          type="text"
          placeholder="Search for restaurants, food..."
          className="w-full p-2 rounded text-black"
        />
      </div>
      <div className="flex gap-4">
        <Link to="#" className="hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded">Sign In</Link>
        <Link to="#" className="px-4 py-2 rounded">Cart</Link>
      </div>
    </nav>
  );
};

export default Navbar;
