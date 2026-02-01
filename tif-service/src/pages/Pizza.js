import React from 'react';
import { useCart } from '../contexts/CartContext';
import { useWishlist } from '../contexts/WishlistContext';
import AddShoppingCartIcon from '@mui/icons-material/AddShoppingCart';
import FavoriteIcon from '@mui/icons-material/Favorite';
import FavoriteBorderIcon from '@mui/icons-material/FavoriteBorder';
import IconButton from '@mui/material/IconButton';
import './Pizza.css';

const Pizza = () => {
  const { addItem } = useCart();
  const { addToWishlist, removeFromWishlist, isInWishlist } = useWishlist();

  const pizzas = [
    { id: 1, name: 'Margherita Pizza', price: 8.99, image: 'Pictures/pizza.jpg' },
    { id: 2, name: 'Pepperoni Pizza', price: 9.99, image: 'Pictures/pizza.jpg' },
    { id: 3, name: 'Veggie Pizza', price: 7.99, image: 'Pictures/pizza.jpg' },
    { id: 4, name: 'BBQ Chicken Pizza', price: 10.49, image: 'Pictures/pizza.jpg' },
    { id: 5, name: 'Hawaiian Pizza', price: 9.49, image: 'Pictures/pizza.jpg' },
  ];

  const handleAddToCart = (pizza) => {
    addItem(pizza);
  };

  const handleToggleWishlist = (pizza) => {
    if (isInWishlist(pizza.id)) {
      removeFromWishlist(pizza.id);
    } else {
      addToWishlist(pizza);
    }
  };

  return (
    <div className="pizza-page">
      <section className="hero">
        <div className="hero-content">
          <h1>Pizzas</h1>
          <input type="text" placeholder="Enter your delivery address" />
          <button>Find Food</button>
        </div>
      </section>

      <section className="food-items">
        <h2>Pizza Items</h2>
        <div className="items-grid">
          {pizzas.map((pizza) => (
            <div key={pizza.id} className="food-item-card">
              <img src={pizza.image} alt={pizza.name} />
              <div className="item-info">
                <h3>{pizza.name}</h3>
                <p className="price">${pizza.price}</p>
                <div className="item-actions">
                  <IconButton
                    onClick={() => handleAddToCart(pizza)}
                    color="primary"
                    size="large"
                  >
                    <AddShoppingCartIcon />
                  </IconButton>
                  <IconButton
                    onClick={() => handleToggleWishlist(pizza)}
                    color={isInWishlist(pizza.id) ? "error" : "default"}
                    size="large"
                  >
                    {isInWishlist(pizza.id) ? <FavoriteIcon /> : <FavoriteBorderIcon />}
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

export default Pizza;
