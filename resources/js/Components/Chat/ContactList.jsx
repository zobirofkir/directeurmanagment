import React from 'react';
import ContactItem from './ContactItem';
import SearchBar from './SearchBar';

const ContactList = ({ contacts, selectedContact, onContactSelect, isSidebarOpen, setIsSidebarOpen }) => {
    return (
        <>
            <div
                className={`w-full md:w-1/4 min-w-[300px] bg-white border-r border-gray-200 fixed md:static h-full z-40 transition-transform duration-300 ${
                    isSidebarOpen ? "translate-x-0" : "-translate-x-full md:translate-x-0"
                }`}
            >
                <div className="p-4 border-b border-gray-200">
                    <div className="mt-[50px]">
                        <SearchBar />
                    </div>
                </div>
                <div className="overflow-y-auto h-[calc(100vh-100px)]">
                    {contacts.map((contact) => (
                        <ContactItem
                            key={contact.id}
                            contact={contact}
                            isSelected={selectedContact?.id === contact.id}
                            onClick={() => {
                                onContactSelect(contact);
                                setIsSidebarOpen(false);
                            }}
                        />
                    ))}
                </div>
            </div>
        </>
    );
};

export default ContactList;
