// src/App.js

import React, { useEffect, useState } from 'react';
import { API_BASE } from './config';    // <-- our new config file
import logo from './logo.svg';
import './App.css';

function App() {
  // 1) State to hold bookings data and any errors
  const [bookings, setBookings] = useState([]);
  const [error, setError]       = useState(null);
  const [loading, setLoading]   = useState(true);

  // 2) Fetch on mount
  useEffect(() => {
    fetch(`${API_BASE}/api/booking`, {
      method: 'GET',
      credentials: 'include',             // <-- send cookies if you need auth
      headers: {
        'Content-Type': 'application/json'
      }
    })
      .then(res => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then(data => {
        setBookings(data);
        setLoading(false);
      })
      .catch(err => {
        setError(err.message);
        setLoading(false);
      });
  }, []);

  // 3) Render
  return (
    <div className="App">
      <header className="App-header">
        {/* Keep your logo if you like */}
        <img src={logo} className="App-logo" alt="logo" />

        <h1>Bookings</h1>

        {loading && <p>Loading bookings…</p>}

        {error && (
          <p style={{ color: 'salmon' }}>
            Error: {error}
          </p>
        )}

        {!loading && !error && (
          <ul>
            {bookings.length === 0 && (
              <li>No bookings found.</li>
            )}
            {bookings.map(b => (
              <li key={b.id}>
                <strong>{b.booking_number}</strong>:&nbsp;
                {new Date(b.check_in).toLocaleString()} →{' '}
                {new Date(b.check_out).toLocaleString()}
              </li>
            ))}
          </ul>
        )}
      </header>
    </div>
  );
}

export default App;
