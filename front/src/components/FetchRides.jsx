// front/src/components/FetchRides.jsx
import React, { useEffect, useState } from 'react';

export default function FetchRides() {
  const [rides, setRides] = useState([]);
  const [loading, setLoading] = useState(false);

  async function loadRides() {
    setLoading(true);
    try {
      const res = await fetch('/api/rides'); // point to your Symfony API route
      if (!res.ok) throw new Error('Erreur réseau');
      const data = await res.json();
      setRides(data);
    } catch (err) {
      console.error('fetch error', err);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    loadRides();
  }, []);

  return (
    <div>
      <h3>Trajets disponibles</h3>
      {loading && <p>Chargement…</p>}
      {!loading && rides.length === 0 && <p>Aucun trajet trouvé.</p>}
      <ul>
        {rides.map(r => (
          <li key={r.id}>
            <strong>{r.departure}</strong> ➜ {r.arrival} — {new Date(r.date_time).toLocaleString()}
          </li>
        ))}
      </ul>
      <button onClick={loadRides}>Rafraîchir</button>
    </div>
  );
}
