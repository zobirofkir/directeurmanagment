import React from 'react';

const SearchBar = () => {
    return (
        <input
            type="text"
            placeholder="Search messages"
            className="w-full px-3 py-2 bg-gray-100 rounded-full focus:outline-none"
        />
    );
};

export default SearchBar;
