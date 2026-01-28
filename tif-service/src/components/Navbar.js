ictoimport React from 'react';
import { Link } from 'react-router-dom';
import { useCart } from '../contexts/CartContext';
import { useWishlist } from '../contexts/WishlistContext';
import ShoppingCartIcon from '@mui/icons-material/ShoppingCart';
import FavoriteIcon from '@mui/icons-material/Favorite';
import Badge from '@mui/material/Badge';
import IconButton from '@mui/material/IconButton';
import './Navbar.css';

const Navbar = () => {
  const { getTotalItems } = useCart();
  const { wishlist } = useWishlist();

  return (
    <nav className="navbar">
      <div className="navbar-logo">
        <Link to="/" className="logo-link">TIF Service</Link>
      </div>
      <div className="navbar-location">
        <span className="location-icon">📍</span>
        <span>Select Location</span>
      </div>
      <div className="navbar-search">
        <input type="text" placeholder="Search for restaurants, food..." />
      </div>
      <div className="navbar-right">
        <Link to="/login" className="nav-link">Sign In</Link>

        {/* Cart Icon with Badge */}
        <IconButton
          component={Link}
          to="/cart"
          className="icon-button"
          color="inherit"
        >
          <Badge badgeContent={getTotalItems()} color="error">
            <ShoppingCartIcon />
          </Badge>
        </IconButton>

        {/* Wishlist Icon with Badge */}
        <IconButton
          component={Link}
          to="/wishlist"
          className="icon-button"
          color="inherit"
        >
          <Badge badgeContent={wishlist.items.length} color="error">
            <FavoriteIcon />
          </Badge>
        </IconButton>
      </div>
    </nav>
  );
};

export default Navbar;
