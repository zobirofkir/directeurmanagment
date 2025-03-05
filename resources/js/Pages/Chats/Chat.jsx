import React, { useState, useEffect, useRef } from "react";
import Echo from "laravel-echo";
import axios from "axios";
import Pusher from "pusher-js";
import ContactList from "@/Components/Chat/ContactList";
import ChatHeader from "@/Components/Chat/ChatHeader";
import MessageList from "@/Components/Chat/MessageList";
import MessageInput from "@/Components/Chat/MessageInput";
import EmojiPicker from "emoji-picker-react";

const Chat = ({ contacts, messages: initialMessages, currentUser }) => {
    const [selectedContact, setSelectedContact] = useState(contacts[0] || null);
    const [messages, setMessages] = useState(initialMessages || []);
    const [newMessage, setNewMessage] = useState("");
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);
    const [showEmojiPicker, setShowEmojiPicker] = useState(false);
    const fileInputRef = useRef(null);

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
        if (
            (!newMessage.trim() && !fileInputRef.current?.files?.length) ||
            !selectedContact
        )
            return;

        try {
            const formData = new FormData();
            formData.append("receiver_id", selectedContact.id);
            if (newMessage.trim()) {
                formData.append("content", newMessage);
            }

            if (fileInputRef.current?.files?.length) {
                formData.append("media", fileInputRef.current.files[0]);
            }

            const response = await axios.post("/messages", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            const sentMessage = {
                id: response.data.id,
                content: response.data.content,
                media_url: response.data.media_url,
                media_type: response.data.media_type,
                time: response.data.time,
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
            if (fileInputRef.current) {
                fileInputRef.current.value = "";
            }
        } catch (error) {
            console.error("Error sending message:", error);
        }
    };

    const onEmojiClick = (emojiObject) => {
        setNewMessage((prevMessage) => prevMessage + emojiObject.emoji);
    };

    const handleFileSelect = () => {
        fileInputRef.current?.click();
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
                <div className="relative">
                    {showEmojiPicker && (
                        <div className="absolute bottom-full right-0 mb-2">
                            <EmojiPicker onEmojiClick={onEmojiClick} />
                        </div>
                    )}
                    <div className="flex items-center p-4 bg-white border-t">
                        <button
                            type="button"
                            onClick={() => setShowEmojiPicker(!showEmojiPicker)}
                            className="p-2 text-gray-500 hover:text-gray-700"
                        >
                            😊
                        </button>
                        <button
                            type="button"
                            onClick={handleFileSelect}
                            className="p-2 text-gray-500 hover:text-gray-700"
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
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </button>
                        <input
                            type="file"
                            ref={fileInputRef}
                            className="hidden"
                            accept="image/*,video/*"
                            onChange={() => {}} // Add validation if needed
                        />
                        <MessageInput
                            newMessage={newMessage}
                            setNewMessage={setNewMessage}
                            onSubmit={handleSendMessage}
                        />
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
