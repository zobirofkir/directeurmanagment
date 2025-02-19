import { toast } from "react-toastify";

export const getCsrfToken = () => {
  return document.querySelector('meta[name="csrf-token"]')?.content || "";
};

export const handleLoginRequest = async (login, password, csrfToken) => {
  if (!csrfToken) {
    toast.error("CSRF token is missing. Please refresh the page and try again.");
    return;
  }

  try {
    const response = await fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({ login, password }),
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();
    if (data.success) {
      window.location.replace('/admin');
    } else {
      toast.error(data.error || "Login failed");
    }
  } catch (error) {
    toast.error("An error occurred. Please try again.");
  }
};
