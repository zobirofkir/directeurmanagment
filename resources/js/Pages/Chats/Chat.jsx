import React, { useState, useEffect, useRef } from "react";
import Echo from "laravel-echo";
import axios from "axios";
import Pusher from "pusher-js";
import ContactList from "@/Components/Chat/ContactList";
import ChatHeader from "@/Components/Chat/ChatHeader";
import MessageList from "@/Components/Chat/MessageList";
import MessageInput from "@/Components/Chat/MessageInput";
import { motion, AnimatePresence } from "framer-motion";
import LogoutButton from "@/Components/LogoutButton";
import notificationSound from "../../assets/notification/notification.mp3";

const Chat = ({ contacts, messages: initialMessages, currentUser }) => {
    const [selectedContact, setSelectedContact] = useState(contacts[0] || null);
    const [messages, setMessages] = useState(initialMessages || []);
    const [newMessage, setNewMessage] = useState("");
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);
    const fileInputRef = useRef(null);
    const [isUploading, setIsUploading] = useState(false);
    const notificationSoundRef = useRef(new Audio(notificationSound));

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
                media_url: e.message.media_url,
                media_type: e.message.media_type,
                time: e.message.created_at
                    ? new Date(e.message.created_at).toLocaleTimeString([], {
                          hour: "2-digit",
                          minute: "2-digit",
                      })
                    : "",
                isSender: false,
                sender: e.message.sender,
            };

            if (selectedContact && e.message.sender.id === selectedContact.id) {
                setMessages((prevMessages) => [...prevMessages, newMessage]);

                // Play notification sound only for received messages
                try {
                    notificationSoundRef.current.play();
                } catch (error) {
                    console.error("Error playing notification:", error);
                }
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
                content: newMessage.trim(),
            });

            const sentMessage = {
                id: response.data.id,
                content: response.data.content,
                time: response.data.time,
                isSender: true,
                sender: response.data.sender,
            };

            setMessages((prevMessages) => [...prevMessages, sentMessage]);
            setNewMessage("");
        } catch (error) {
            console.error("Error sending message:", error);
            alert(error.response?.data?.message || "Failed to send message");
        }
    };

    return (
        <div className="flex h-screen bg-gray-100">
            {/* Header Bar - New Addition */}
            <div className="fixed top-0 left-0 right-0 h-16 bg-white shadow-sm z-40 flex items-center justify-between px-4 md:px-6">
                {/* Mobile Sidebar Toggle Button */}
                <button
                    className="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
                    onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-6 w-6 text-gray-600"
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

                {/* App Title/Logo */}
                <div className="hidden md:block text-xl font-semibold text-gray-800">
                    Chat App
                </div>

                {/* Logout Button */}
                <LogoutButton />
            </div>

            {/* Main Content - Updated with top padding */}
            <div className="flex w-full pt-16">
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
                    <div className="relative w-full">
                        <div className="flex items-center p-4 bg-white border-t w-full">
                            <div className="flex items-center space-x-2 w-full max-w-6xl mx-auto">
                                <div className="flex-1">
                                    <MessageInput
                                        newMessage={newMessage}
                                        setNewMessage={setNewMessage}
                                        onSubmit={handleSendMessage}
                                    />
                                </div>
                            </div>
                        </div>
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
