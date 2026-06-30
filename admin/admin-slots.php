<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Management - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'inc/admin_header.php'; ?>

<section id="slots" class="page active">
    <h2>Slot Management</h2>
    <p>View, add, edit, and delete appointment slots.</p>

    <div class="actions">
        <button id="createSlotBtn" onclick="showSlotForm()">Add New Slot +</button>
    </div>

    <div id="slotForm" class="form-card hidden">
        <label>Date:
            <input id="slotDate" type="date">
        </label>

        <label>Start:
            <input id="slotStartTime" type="time">
        </label>

        <label>End:
            <input id="slotEndTime" type="time">
        </label>

        <label>Type:
            <select id="slotType">
    <option>Extra Slot</option>
    <option>Emergency Slot</option>
</select>
        </label>

        <label>Capacity:
            <input id="slotCapacity" type="number" min="1">
        </label>

        <button class="green" onclick="createSlot()">Create</button>
        <button class="red" onclick="hideSlotForm()">Cancel</button>
    </div>

    <div class="filter-bar">
        <input type="text" id="slotSearch" placeholder="Search by date, type, or time..." onkeyup="filterSlots()">

        <select id="slotTypeFilter" onchange="filterSlots()">
    <option value="All">All Slot Types</option>
    <option value="Same-Day Morning">Same-Day Morning</option>
    <option value="Same-Day Afternoon">Same-Day Afternoon</option>
    <option value="Scheduled">Scheduled</option>
    <option value="Extra Slot">Extra Slot</option>
    <option value="Emergency Slot">Emergency Slot</option>
</select>
    </div>

    <div class="table-card">
        <table class="admin-table">
            <thead>
                <tr>
    <th>Slot ID</th>
    <th>Date</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Slot Type</th>
    <th>Capacity</th>
    <th>Booked</th>
    <th>Status</th>
    <th>Action</th>
</tr>
            </thead>

            <tbody id="slotTableBody"></tbody>
        </table>
    </div>
</section>

<div id="confirmBox" class="modal hidden">
    <div class="modal-card">
        <b id="confirmText">Are you sure?</b>
        <button class="green" onclick="confirmYes()">Confirm</button>
        <button class="red" onclick="closeConfirm()">Cancel</button>
    </div>
</div>

<script src="admin.js"></script>
</body>
</html>