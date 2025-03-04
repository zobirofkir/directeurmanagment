import React, { useState } from "react";

const Chat = () => {
    const contacts = [
        {
            id: 1,
            name: "John Doe",
            avatar: "https://ui-avatars.com/api/?name=John+Doe",
            online: true,
            lastMessage: "Hey, how are you?",
            time: "12:30 PM",
        },
        {
            id: 2,
            name: "Jane Smith",
            avatar: "https://ui-avatars.com/api/?name=Jane+Smith",
            online: false,
            lastMessage: "See you tomorrow!",
            time: "11:45 AM",
        },
        {
            id: 3,
            name: "Mike Johnson",
            avatar: "https://ui-avatars.com/api/?name=Mike+Johnson",
            online: true,
            lastMessage: "Thanks for your help!",
            time: "10:20 AM",
        },
    ];

    const [selectedContact, setSelectedContact] = useState(contacts[0]);
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    const messages = [
        {
            id: 1,
            sender: "John Doe",
            content: "Hey, how are you?",
            time: "12:30 PM",
            isSender: false,
        },
        {
            id: 2,
            sender: "You",
            content: "I'm good, thanks! How about you?",
            time: "12:31 PM",
            isSender: true,
        },
        {
            id: 3,
            sender: "John Doe",
            content: "Doing great! Want to grab coffee later?",
            time: "12:32 PM",
            isSender: false,
        },
    ];

    return (
        <div className="flex h-screen bg-gray-100">
            {/* Mobile Sidebar Toggle Button */}
            <button
                className="md:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg"
                onClick={() => setIsSidebarOpen(!isSidebarOpen)}
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    className="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>

            {/* Contacts Sidebar - Modified for responsive */}
            <div
                className={`w-full md:w-1/4 min-w-[300px] bg-white border-r border-gray-200 fixed md:static h-full z-40 transition-transform duration-300 ${
                    isSidebarOpen
                        ? "translate-x-0"
                        : "-translate-x-full md:translate-x-0"
                }`}
            >
                <div className="p-4 border-b border-gray-200">

                    <div className="mt-[50px]">
                        <input
                            type="text"
                            placeholder="Search messages"
                            className="w-full px-3 py-2 bg-gray-100 rounded-full focus:outline-none"
                        />
                    </div>
                </div>
                <div className="overflow-y-auto h-[calc(100vh-100px)]">
                    {contacts.map((contact) => (
                        <div
                            key={contact.id}
                            className={`flex items-center p-3 hover:bg-gray-50 cursor-pointer ${
                                selectedContact.id === contact.id
                                    ? "bg-gray-100"
                                    : ""
                            }`}
                            onClick={() => {
                                setSelectedContact(contact);
                                setIsSidebarOpen(false);
                            }}
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
                                    <h3 className="font-semibold">
                                        {contact.name}
                                    </h3>
                                    <span className="text-xs text-gray-500">
                                        {contact.time}
                                    </span>
                                </div>
                                <p className="text-sm text-gray-500 truncate">
                                    {contact.lastMessage}
                                </p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {/* Chat Area - Modified to show selected contact */}
            <div
                className={`flex-1 flex flex-col ${
                    !selectedContact && "hidden md:flex"
                }`}
            >
                {/* Chat Header - Updated to show selected contact */}
                <div className="p-4 bg-white border-b border-gray-200 flex items-center">
                    <img
                        src={selectedContact.avatar}
                        alt={selectedContact.name}
                        className="w-10 h-10 rounded-full"
                    />
                    <div className="ml-3">
                        <h3 className="font-semibold">
                            {selectedContact.name}
                        </h3>
                        <p className="text-xs text-green-500">
                            {selectedContact.online ? "Active now" : "Offline"}
                        </p>
                    </div>
                </div>

                {/* Messages */}
                <div className="flex-1 overflow-y-auto p-4 bg-gray-50">
                    <div className="space-y-4">
                        {messages.map((message) => (
                            <div
                                key={message.id}
                                className={`flex ${
                                    message.isSender
                                        ? "justify-end"
                                        : "justify-start"
                                }`}
                            >
                                <div
                                    className={`max-w-[70%] rounded-lg p-3 ${
                                        message.isSender
                                            ? "bg-blue-500 text-white"
                                            : "bg-white border border-gray-200"
                                    }`}
                                >
                                    <p>{message.content}</p>
                                    <p
                                        className={`text-xs mt-1 ${
                                            message.isSender
                                                ? "text-blue-100"
                                                : "text-gray-500"
                                        }`}
                                    >
                                        {message.time}
                                    </p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Message Input */}
                <div className="p-4 bg-white border-t border-gray-200">
                    <div className="flex items-center space-x-2">
                        <button className="p-2 hover:bg-gray-100 rounded-full">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                className="h-6 w-6 text-gray-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </button>
                        <input
                            type="text"
                            placeholder="Type a message..."
                            className="flex-1 px-4 py-2 bg-gray-100 rounded-full focus:outline-none"
                        />
                        <button className="p-2 hover:bg-gray-100 rounded-full">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                className="h-6 w-6 text-blue-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {/* Overlay for mobile */}
            {isSidebarOpen && (
                <div
                    className="md:hidden fixed inset-0 bg-black bg-opacity-50 z-30"
                    onClick={() => setIsSidebarOpen(false)}
                ></div>
            )}
        </div>
    );
};

export default Chat;
