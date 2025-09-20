import React from 'react';

type ProductProps = {
    image: string;
    name: string;
    price: number | string;
};

const Product: React.FC<ProductProps> = ({ image, name, price }) => (
    <div className="w-44 border border-gray-200 rounded-lg p-3 shadow-sm flex flex-col items-center bg-white">
        <img
            src={image}
            alt={name}
            className="w-30 h-30 object-cover rounded-md mb-2.5"
        />
        <div className="font-medium text-base mb-1.5 text-center text-gray-800">
            {name}
        </div>
        <div className="text-green-700 font-semibold text-sm mb-2">
            ${price}
        </div>
        <button
            type="button"
            className="w-full py-2 mt-2 rounded-md bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition-colors duration-150 shadow focus:outline-none focus:ring-2 focus:ring-blue-400"
            aria-label="Buy"
        >
            Buy
        </button>
    </div>
);

export default Product;
