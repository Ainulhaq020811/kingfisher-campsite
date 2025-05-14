// src/config.js

/**
 * Central place for your API base URL.
 * In development you’ll hit localhost:8080,
 * in production you can override via REACT_APP_API_BASE.
 */
export const API_BASE =
  process.env.REACT_APP_API_BASE || 'http://localhost:8080';
