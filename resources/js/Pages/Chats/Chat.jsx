import React, { useState, useEffect } from "react";
import Echo from "laravel-echo";
import axios from "axios";
import Pusher from "pusher-js";
import ContactList from "@/Components/Chat/ContactList";
import ChatHeader from "@/Components/Chat/ChatHeader";
import MessageList from "@/Components/Chat/MessageList";
import MessageInput from "@/Components/Chat/MessageInput";

const Chat = ({ contacts, messages: initialMessages, currentUser }) => {
    const [selectedContact, setSelectedContact] = useState(contacts[0] || null);
    const [messages, setMessages] = useState(initialMessages || []);
    const [newMessage, setNewMessage] = useState("");
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    useEffect(() => {
        if (!currentUser?.id || !import.meta.env.VITE_PUSHER_APP_KEY) {
            return;
        }

        // Initialize Pusher globally
        window.Pusher = Pusher;

        const echo = new Echo({
            broadcaster: "pusher",
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            authorizer: (channel) => ({
                authorize: async (socketId, callback) => {
                    try {
                        const response = await axios.post(
                            "/broadcasting/auth",
                            {
                                socket_id: socketId,
                                channel_name: channel.name,
                            }
                        );
                        callback(null, response.data);
                    } catch (error) {
                        callback(error);
                    }
                },
            }),
        });

        echo.private(`chat.${currentUser.id}`).listen("MessageSent", (e) => {
            const newMessage = {
                id: e.message.id,
                content: e.message.content,
                time: e.message.created_at
                    ? new Date(e.message.created_at).toLocaleTimeString([], {
                          hour: "2-digit",
                          minute: "2-digit",
                      })
                    : "",
                isSender: false,
                sender: {
                    id: e.message.sender.id,
                    name: e.message.sender.name,
                    avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(
                        e.message.sender.name
                    )}&color=7F9CF5&background=EBF4FF`,
                },
            };

            if (selectedContact && e.message.sender.id === selectedContact.id) {
                setMessages((prevMessages) => [...prevMessages, newMessage]);
            }
        });

        return () => {
            echo.leave(`chat.${currentUser.id}`);
        };
    }, [currentUser?.id, selectedContact]);

    const handleContactSelect = async (contact) => {
        setSelectedContact(contact);
        const response = await axios.get(`/messages/${contact.id}`);
        setMessages(response.data);
    };

    const handleSendMessage = async (e) => {
        e.preventDefault();
        if (!newMessage.trim() || !selectedContact) return;

        try {
            const response = await axios.post("/messages", {
                receiver_id: selectedContact.id,
                content: newMessage,
            });

            const sentMessage = {
                id: response.data.id,
                content: response.data.content,
                time: response.data.created_at
                    ? new Date(response.data.created_at).toLocaleTimeString(
                          [],
                          {
                              hour: "2-digit",
                              minute: "2-digit",
                          }
                      )
                    : "",
                isSender: true,
                sender: {
                    id: currentUser.id,
                    name: currentUser.name,
                    avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(
                        currentUser.name
                    )}&color=7F9CF5&background=EBF4FF`,
                },
            };

            setMessages((prevMessages) => [...prevMessages, sentMessage]);
            setNewMessage("");
        } catch (error) {
            console.error("Error sending message:", error);
        }
    };

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

            <ContactList
                contacts={contacts}
                selectedContact={selectedContact}
                onContactSelect={handleContactSelect}
                isSidebarOpen={isSidebarOpen}
                setIsSidebarOpen={setIsSidebarOpen}
            />

            <div
                className={`flex-1 flex flex-col ${
                    !selectedContact && "hidden md:flex"
                }`}
            >
                <ChatHeader selectedContact={selectedContact} />
                <MessageList messages={messages} />
                <MessageInput
                    newMessage={newMessage}
                    setNewMessage={setNewMessage}
                    onSubmit={handleSendMessage}
                />
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
