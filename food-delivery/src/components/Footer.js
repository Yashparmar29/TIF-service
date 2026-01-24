import React from 'react';

const Footer = () => {
  return (
    <footer className="bg-gray-800 text-white p-8 text-center">
      <div className="flex justify-center gap-8 mb-4">
        <a href="#" className="hover:underline">About Us</a>
        <a href="#" className="hover:underline">Contact</a>
        <a href="#" className="hover:underline">Privacy Policy</a>
        <a href="#" className="hover:underline">Terms of Service</a>
      </div>
      <div className="mb-4">
        <p>Email: support@tif-service.com | Phone: +1 (123) 456-7890</p>
      </div>
      <div className="text-gray-400">
        <p>&copy; 2023 TIF Service. All rights reserved.</p>
      </div>
    </footer>
  );
};

export default Footer;
