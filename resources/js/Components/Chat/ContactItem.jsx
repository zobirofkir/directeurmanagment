import React from 'react';

const ContactItem = ({ contact, isSelected, onClick }) => {
    return (
        <div
            className={`flex items-center p-3 hover:bg-gray-50 cursor-pointer ${
                isSelected ? "bg-gray-100" : ""
            }`}
            onClick={onClick}
        >
            <div className="relative">
                <img
                    src={contact.avatar}
                    alt={contact.name}
                    className="w-12 h-12 rounded-full"
                />
                {contact.online && (
                    <div className="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                )}
            </div>
            <div className="ml-3 flex-1">
                <div className="flex justify-between items-center">
                    <h3 className="font-semibold">{contact.name}</h3>
                    <span className="text-xs text-gray-500">
                        {contact.last_message_time}
                    </span>
                </div>
                <p className="text-sm text-gray-500 truncate">
                    {contact.last_message}
                </p>
            </div>
        </div>
    );
};

export default ContactItem;
