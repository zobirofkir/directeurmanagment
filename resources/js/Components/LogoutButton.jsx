import React from 'react';
import axios from 'axios';

const LogoutButton = () => {
    const handleLogout = async () => {
        try {
            await axios.post('/logout');
            window.location.href = '/';
        } catch (error) {
            console.error('Logout failed:', error);
        }
    };

    return (
        <button
            onClick={handleLogout}
            className="group relative flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg
            shadow-md hover:bg-gray-50 transition-all duration-200 ease-in-out
            border border-gray-200 hover:border-gray-300
            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            {/* Logout Icon */}
            <svg
                xmlns="http://www.w3.org/2000/svg"
                className="h-5 w-5 text-gray-500 group-hover:text-gray-700 transition-colors"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
            </svg>
            <span className="hidden md:inline">Logout</span>

            {/* Tooltip for mobile */}
            <span className="absolute hidden group-hover:block md:hidden -bottom-8 left-1/2 -translate-x-1/2
                px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                Logout
            </span>
        </button>
    );
};

export default LogoutButton;
