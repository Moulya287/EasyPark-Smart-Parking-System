# 🚗 EasyPark – Smart Parking System

<p align="center">
  <a href="https://drive.google.com/file/d/1ThlF6ccpVWwMO68DUzRCA4vhzrE1wmix/view?usp=sharing">
    <img src="https://img.shields.io/badge/🎥%20Watch%20Demo-4285F4?style=for-the-badge&logo=google-drive&logoColor=white" alt="Watch Demo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/IoT-ESP32-success?style=for-the-badge">
  <img src="https://img.shields.io/badge/PHP-Backend-blue?style=for-the-badge">
  <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge">
  <img src="https://img.shields.io/badge/Status-Completed-brightgreen?style=for-the-badge">
</p>

---

## 📖 Overview

EasyPark is an IoT-powered Smart Parking Management System designed to automate parking slot monitoring, reservation, and occupancy management through seamless integration of hardware and software components.

The system combines ESP32 microcontrollers, ultrasonic sensors, LED indicators, PHP, MySQL, and a web-based dashboard to provide real-time parking availability and smart reservation management. The goal is to reduce parking search time, improve parking efficiency, and contribute to smarter urban infrastructure.

---

## 🎯 Problem Statement

Finding available parking spaces in busy urban environments is often time-consuming and inefficient. Traditional parking systems lack:

- Real-time slot availability
- Automated monitoring
- Online reservation capabilities
- Efficient parking management

EasyPark addresses these challenges through IoT-based automation and real-time monitoring.

---

## ✨ Key Features

### 🚘 Smart Vehicle Detection
- Ultrasonic sensor-based occupancy detection
- Automatic slot status updates
- Real-time monitoring

### 💡 LED Status Indicators
- 🟢 Green → Empty Slot
- 🟡 Yellow → Reserved Slot
- 🔴 Red → Occupied Slot

### 🌐 Real-Time Dashboard
- Live parking status monitoring
- Automatic dashboard refresh
- Remote accessibility

### 📅 Reservation System
- Online parking slot booking
- Automated slot assignment
- Reservation duration management

### 🔐 Admin Dashboard
- Secure admin authentication
- Live slot monitoring
- Reservation management
- Real-time status tracking

### 🗄️ Database Integration
- MySQL-powered backend
- Reservation records
- Admin management
- Persistent data storage

---

## 🏗️ System Architecture

```text
Vehicle Detection
(Ultrasonic Sensors)
          │
          ▼
ESP32 Microcontroller
          │
          ▼
Wi-Fi Communication
          │
          ▼
PHP Backend + MySQL Database
          │
          ▼
Web Dashboard
          │
          ▼
Admin Monitoring & User Reservations
```

---

## ⚙️ Technology Stack

### Hardware
- ESP32 Development Board
- HC-SR04 Ultrasonic Sensors
- LED Indicators
- Breadboard
- Jumper Wires
- Resistors
- 5V Power Supply

### Software
- Arduino IDE
- PHP
- MySQL
- phpMyAdmin
- HTML5
- CSS3
- JavaScript
- XAMPP

---

## 📂 Project Modules

### User Module
- View available parking slots
- Reserve parking spaces
- Select booking duration

### Hardware Module
- Vehicle detection
- Slot occupancy monitoring
- LED status control

### Admin Module
- Secure login system
- Live dashboard monitoring
- Reservation tracking
- Parking management

### Database Module
- Reservation storage
- Admin credentials
- Slot assignment tracking

---

## 🔄 Workflow

1. User selects a parking venue.
2. User reserves an available parking slot.
3. Reservation information is stored in MySQL.
4. ESP32 receives slot reservation data.
5. Yellow LED indicates reserved status.
6. Ultrasonic sensors monitor vehicle presence.
7. Red LED activates when the slot becomes occupied.
8. Dashboard updates automatically in real time.
9. Admin monitors parking activity through the dashboard.

---

## 📊 Parking Slot Logic

| Slot Status | LED Color |
|------------|------------|
| Empty | 🟢 Green |
| Reserved | 🟡 Yellow |
| Occupied | 🔴 Red |

---

## 🎥 Demo Video

Click below to watch the project demonstration:

<p align="center">
  <a href="https://drive.google.com/file/d/1ThlF6ccpVWwMO68DUzRCA4vhzrE1wmix/view?usp=sharing">
    <img src="https://img.shields.io/badge/▶%20Watch%20Project%20Demo-blue?style=for-the-badge" alt="Demo Video">
  </a>
</p>

---

## 🎯 Project Outcomes

✅ Automated parking slot detection

✅ Real-time monitoring and management

✅ Smart reservation system

✅ Improved parking efficiency

✅ Reduced vehicle search time

✅ Scalable IoT architecture

✅ Enhanced user experience

---

## 🚀 Future Enhancements

- Mobile Application Integration
- QR Code-Based Entry System
- License Plate Recognition
- Online Payment Gateway
- AI-Based Parking Analytics
- Cloud Deployment
- Multi-Venue Support

---

## 👨‍💻 Team Members

- Moulya N
- Nama Shritha
- Nireeksha P Rathod
- Pooja Naresh M

---

## 🎓 Academic Information

**Project:** EasyPark – Smart Parking System

**Course:** Internet of Things (CS2404)

**Institution:** RV University, Bengaluru

**Program:** B.Tech (Honors) – Computer Science & Engineering

**Academic Year:** 2025–2026

---

## 📜 License

This project was developed for academic and educational purposes.

---

## ⭐ Support

If you found this project interesting, consider giving it a ⭐ on GitHub.
