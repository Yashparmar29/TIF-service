dyimport React from 'react';
import { useCart } from '../contexts/CartContext';
import { useWishlist } from '../contexts/WishlistContext';
import DeleteIcon from '@mui/icons-material/Delete';
import AddIcon from '@mui/icons-material/Add';
import RemoveIcon from '@mui/icons-material/Remove';
import IconButton from '@mui/material/IconButton';
import Button from '@mui/material/Button';
import './Cart.css';

const Cart = () => {
  const { cart, removeItem, updateQuantity, clearCart, getTotalPrice } = useCart();
  const { addToWishlist, removeFromWishlist, isInWishlist } = useWishlist();

  const handleQuantityChange = (itemId, newQuantity) => {
    updateQuantity(itemId, newQuantity);
  };

  const handleMoveToWishlist = (item) => {
    if (!isInWishlist(item.id)) {
      addToWishlist(item);
    }
    removeItem(item.id);
  };

  if (cart.items.length === 0) {
    return (
      <div className="cart-container">
        <h2>Your Cart</h2>
        <div className="empty-cart">
          <p>Your cart is empty</p>
          <Button variant="contained" color="primary">
            Continue Shopping
          </Button>
        </div>
      </div>
    );
  }

  return (
    <div className="cart-container">
      <div className="cart-header">
        <h2>Your Cart ({cart.items.length} items)</h2>
        <Button variant="outlined" onClick={clearCart}>
          Clear Cart
        </Button>
      </div>

      <div className="cart-items">
        {cart.items.map((item) => (
          <div key={item.id} className="cart-item">
            <div className="item-image">
              <img src={item.image} alt={item.name} />
            </div>
            <div className="item-details">
              <h3>{item.name}</h3>
              <p className="item-price">${item.price}</p>
            </div>
            <div className="item-controls">
              <div className="quantity-controls">
                <IconButton
                  size="small"
                  onClick={() => handleQuantityChange(item.id, item.quantity - 1)}
                >
                  <RemoveIcon />
                </IconButton>
                <span className="quantity">{item.quantity}</span>
                <IconButton
                  size="small"
                  onClick={() => handleQuantityChange(item.id, item.quantity + 1)}
                >
                  <AddIcon />
                </IconButton>
              </div>
              <div className="item-actions">
                <Button
                  size="small"
                  onClick={() => handleMoveToWishlist(item)}
                  disabled={isInWishlist(item.id)}
                >
                  {isInWishlist(item.id) ? 'In Wishlist' : 'Move to Wishlist'}
                </Button>
                <IconButton
                  size="small"
                  onClick={() => removeItem(item.id)}
                  color="error"
                >
                  <DeleteIcon />
                </IconButton>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="cart-footer">
        <div className="cart-total">
          <h3>Total: ${getTotalPrice().toFixed(2)}</h3>
        </div>
        <Button variant="contained" color="primary" size="large">
          Proceed to Checkout
        </Button>
      </div>
    </div>
  );
};

export default Cart;
