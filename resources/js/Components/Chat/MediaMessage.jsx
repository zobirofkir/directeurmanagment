import React from 'react';

const MediaMessage = ({ mediaUrl, mediaType }) => {
    if (mediaType === 'image') {
        return (
            <img
                src={mediaUrl}
                alt="Shared image"
                className="max-w-[200px] rounded-lg cursor-pointer hover:opacity-90"
                onClick={() => window.open(mediaUrl, '_blank')}
            />
        );
    } else if (mediaType === 'video') {
        return (
            <video
                controls
                className="max-w-[200px] rounded-lg"
            >
                <source src={mediaUrl} />
                Your browser does not support the video tag.
            </video>
        );
    }
    return null;
};

export default MediaMessage;
