simport React from 'react';
import { useWishlist } from '../contexts/WishlistContext';
import { useCart } from '../contexts/CartContext';
import DeleteIcon from '@mui/icons-material/Delete';
import AddShoppingCartIcon from '@mui/icons-material/AddShoppingCart';
import IconButton from '@mui/material/IconButton';
import Button from '@mui/material/Button';
import './Wishlist.css';

const Wishlist = () => {
  const { wishlist, removeFromWishlist, clearWishlist } = useWishlist();
  const { addItem } = useCart();

  const handleMoveToCart = (item) => {
    addItem(item);
    removeFromWishlist(item.id);
  };

  if (wishlist.items.length === 0) {
    return (
      <div className="wishlist-container">
        <h2>Your Wishlist</h2>
        <div className="empty-wishlist">
          <p>Your wishlist is empty</p>
          <Button variant="contained" color="primary">
            Continue Shopping
          </Button>
        </div>
      </div>
    );
  }

  return (
    <div className="wishlist-container">
      <div className="wishlist-header">
        <h2>Your Wishlist ({wishlist.items.length} items)</h2>
        <Button variant="outlined" onClick={clearWishlist}>
          Clear Wishlist
        </Button>
      </div>

      <div className="wishlist-items">
        {wishlist.items.map((item) => (
          <div key={item.id} className="wishlist-item">
            <div className="item-image">
              <img src={item.image} alt={item.name} />
            </div>
            <div className="item-details">
              <h3>{item.name}</h3>
              <p className="item-price">${item.price}</p>
            </div>
            <div className="item-actions">
              <Button
                variant="contained"
                startIcon={<AddShoppingCartIcon />}
                onClick={() => handleMoveToCart(item)}
              >
                Add to Cart
              </Button>
              <IconButton
                onClick={() => removeFromWishlist(item.id)}
                color="error"
                size="large"
              >
                <DeleteIcon />
              </IconButton>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Wishlist;
