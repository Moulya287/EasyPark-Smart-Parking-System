<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EasyPark Admin Dashboard</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body {
      background-color: #f8f9fa;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background-color: #007bff;
      color: white;
      padding: 15px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
    }

    .header-left { display: flex; align-items: center; gap: 10px; }
    .header-left img { height: 60px; }
    .header-title {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      font-size: 26px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    #logout {
      background-color: #ffffff;
      color: #007bff;
      border: none;
      border-radius: 8px;
      padding: 10px 25px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }
    #logout:hover { background-color: #e0e0e0; }

    .dashboard {
      flex-grow: 1;
      text-align: center;
      padding: 60px 20px 100px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .dashboard h2 {
      font-size: 34px;
      color: #007bff;
      margin-bottom: 50px;
    }

    .slots {
      display: flex;
      justify-content: center;
      gap: 60px;
      flex-wrap: wrap;
    }

    .slot {
      border-radius: 20px;
      width: 240px;
      height: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 20px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.2);
      transition: transform 0.2s ease;
    }

    .slot:hover { transform: scale(1.05); }

    .slot p {
      font-size: 18px;
      margin-top: 10px;
      text-transform: capitalize;
    }

    .empty { background-color: #28a745; }
    .occupied { background-color: #dc3545; }
    .reserved { background-color: #ffc107; color: #333; font-weight: 600; }
    .disconnected { background-color: #6c757d; }

    footer {
      background-color: #007bff;
      color: white;
      text-align: center;
      padding: 15px 0;
      font-size: 15px;
      margin-top: auto;
    }

    @media (max-width: 768px) {
      .slots { flex-direction: column; gap: 30px; }
      .header-title { font-size: 22px; }
    }
  </style>
</head>
<body>

  <header>
    <div class="header-left">
      <img src="Screenshot_2025-10-06_160522-removebg-preview.png" alt="EasyPark Logo">
      <h1>EasyPark</h1>
    </div>
    <div class="header-title">ADMIN DASHBOARD</div>
    <button id="logout" onclick="logout()">Logout</button>
  </header>

  <section class="dashboard">
    <h2>Live Slot Status</h2>

    <div class="slots">
      <div id="slot1" class="slot disconnected">
        <div>Slot 1</div>
        <p>Status: Disconnected</p>
      </div>

      <div id="slot2" class="slot disconnected">
        <div>Slot 2</div>
        <p>Status: Disconnected</p>
      </div>
    </div>
  </section>

  <footer>
    © 2025 EasyPark | Smart Parking System
  </footer>

<script>
async function fetchSlotStatus() {
  try {
    const response = await fetch("esp_proxy.php", { cache: "no-store" });
    if (!response.ok) throw new Error("ESP not responding");

    const data = await response.json();
    console.log("ESP32 Data:", data);

    updateSlot("slot1", data.slot1);
    updateSlot("slot2", data.slot2);
  } catch (err) {
    console.error("Error fetching ESP32 data:", err);
    updateSlot("slot1", "disconnected");
    updateSlot("slot2", "disconnected");
  }
}

function updateSlot(id, status) {
  const box = document.getElementById(id);
  box.className = "slot"; // reset classes
  status = status.toLowerCase();

  let colorClass = "disconnected";
  let text = "Disconnected";

  if (status === "occupied") { colorClass = "occupied"; text = "Occupied"; }
  else if (status === "empty") { colorClass = "empty"; text = "Empty"; }
  else if (status === "reserved") { colorClass = "reserved"; text = "Reserved"; }

  box.classList.add(colorClass);
  box.innerHTML = `<div>${id.toUpperCase()}</div><p>Status: ${text}</p>`;
}

// 🔁 Auto update every 2 seconds
setInterval(fetchSlotStatus, 2000);
fetchSlotStatus();

function logout() {
  window.location.href = "adminlogin.html"; // redirect to login page
}
</script>

</body>
</html>
