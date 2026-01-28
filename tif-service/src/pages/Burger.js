import React from 'react';
import { useCart } from '../contexts/CartContext';
import { useWishlist } from '../contexts/WishlistContext';
import AddShoppingCartIcon from '@mui/icons-material/AddShoppingCart';
import FavoriteIcon from '@mui/icons-material/Favorite';
import FavoriteBorderIcon from '@mui/icons-material/FavoriteBorder';
import IconButton from '@mui/material/IconButton';
import './Burger.css';

const Burger = () => {
  const { addItem } = useCart();
  const { addToWishlist, removeFromWishlist, isInWishlist } = useWishlist();

  const burgers = [
    { id: 6, name: 'Classic Cheeseburger', price: 7.99, image: 'Pictures/burger.jfif' },
    { id: 7, name: 'Bacon Burger', price: 9.49, image: 'Pictures/burger.jfif' },
    { id: 8, name: 'Veggie Burger', price: 8.49, image: 'Pictures/burger.jfif' },
    { id: 9, name: 'Double Patty Burger', price: 10.99, image: 'Pictures/burger.jfif' },
    { id: 10, name: 'Spicy Chicken Burger', price: 8.99, image: 'Pictures/burger.jfif' },
  ];

  const handleAddToCart = (burger) => {
    addItem(burger);
  };

  const handleToggleWishlist = (burger) => {
    if (isInWishlist(burger.id)) {
      removeFromWishlist(burger.id);
    } else {
      addToWishlist(burger);
    }
  };

  return (
    <div className="burger-page">
      <section className="hero">
        <div className="hero-content">
          <h1>Burgers</h1>
          <input type="text" placeholder="Enter your delivery address" />
          <button>Find Food</button>
        </div>
      </section>

      <section className="food-items">
        <h2>Burger Items</h2>
        <div className="items-grid">
          {burgers.map((burger) => (
            <div key={burger.id} className="food-item-card">
              <img src={burger.image} alt={burger.name} />
              <div className="item-info">
                <h3>{burger.name}</h3>
                <p className="price">${burger.price}</p>
                <div className="item-actions">
                  <IconButton
                    onClick={() => handleAddToCart(burger)}
                    color="primary"
                    size="large"
                  >
                    <AddShoppingCartIcon />
                  </IconButton>
                  <IconButton
                    onClick={() => handleToggleWishlist(burger)}
                    color={isInWishlist(burger.id) ? "error" : "default"}
                    size="large"
                  >
                    {isInWishlist(burger.id) ? <FavoriteIcon /> : <FavoriteBorderIcon />}
                  </IconButton>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
};

export default Burger;
