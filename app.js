document.addEventListener("DOMContentLoaded", () => {
  const SESSIONS = 6;
  let chart = null;
  let flashing = null;

  // compute attendance + participation
  function computeRow(row) {
    const s = [...row.querySelectorAll(".s")];
    const p = [...row.querySelectorAll(".p")];
    const present = s.filter(x => x.checked).length;
    const part = p.filter(x => x.checked).length;
    const abs = SESSIONS - present;

    row.querySelector(".absences").textContent = abs;
    row.querySelector(".participation-count").textContent = part;

    // message logic
    let msg = "";
    if (present === 0 && part === 0) {
      msg = ""; // no message if nothing yet
      row.classList.remove("row-good", "row-warning", "row-bad");
    } else if (abs < 3 && part >= 3) {
      msg = "Excellent student";
      setColor(row, "good");
    } else if (abs < 3 && part < 3) {
      msg = "Good attendance, needs more participation";
      setColor(row, "good");
    } else if (abs >= 3 && abs <= 4) {
      msg = "Warning: low attendance";
      setColor(row, "warning");
    } else {
      msg = "Excluded: too many absences";
      setColor(row, "bad");
    }

    row.querySelector(".message-cell").textContent = msg;
  }

  function setColor(row, type) {
    row.classList.remove("row-good", "row-warning", "row-bad");
    if (type === "good") row.classList.add("row-good");
    if (type === "warning") row.classList.add("row-warning");
    if (type === "bad") row.classList.add("row-bad");
  }

  function computeAll() {
    document.querySelectorAll(".student-row").forEach(r => computeRow(r));
    updateReport();
  }

  // Checkbox live update
  const table = document.getElementById("attendance-table");
  table.addEventListener("change", e => {
    if (e.target.matches(".s,.p")) {
      computeRow(e.target.closest(".student-row"));
    }
  });

  // Add student form with PHP validation
  const form = document.getElementById("student-form");
  const idField = document.getElementById("student-id");
  const lastField = document.getElementById("last-name");
  const firstField = document.getElementById("first-name");
  const emailField = document.getElementById("email");
  const confirmMsg = document.getElementById("confirmation");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const id = idField.value.trim();
    const last = lastField.value.trim();
    const first = firstField.value.trim();
    const email = emailField.value.trim();

    // Basic client-side validation
    if (!id || !last || !first || !email) {
      confirmMsg.textContent = "All fields required!";
      confirmMsg.style.color = "red";
      return;
    }

    try {
      // Send data to PHP for validation
      const formData = new FormData();
      formData.append('student_id', id);
      formData.append('last_name', last);
      formData.append('first_name', first);
      formData.append('email', email);

      const response = await fetch('form.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.status === 'success') {
        // PHP validation passed - add student to table
        const tbody = table.querySelector("tbody");
        const row = document.createElement("tr");
        row.className = "student-row";
        row.innerHTML = `
          <td class="sid">${id}</td>
          <td class="lname">${last}</td>
          <td class="fname">${first}</td>
          ${Array(SESSIONS).fill('<td><input type="checkbox" class="s"></td>').join("")}
          ${Array(SESSIONS).fill('<td><input type="checkbox" class="p"></td>').join("")}
          <td class="absences">${SESSIONS}</td>
          <td class="participation-count">0</td>
          <td class="message-cell"></td>
        `;
        tbody.appendChild(row);

        confirmMsg.textContent = result.message;
        confirmMsg.style.color = "green";
        form.reset();

        computeRow(row);
        updateReport();
      } else {
        // PHP validation failed - show errors
        let errorMessage = "";
        for (const field in result.errors) {
          errorMessage += `${result.errors[field]}\n`;
        }
        confirmMsg.textContent = errorMessage;
        confirmMsg.style.color = "red";
      }
    } catch (error) {
      confirmMsg.textContent = "Error submitting form. Please try again.";
      confirmMsg.style.color = "red";
      console.error('Error:', error);
    }
  });

  // Report
  function updateReport() {
    const rows = [...document.querySelectorAll(".student-row")];
    const totals = {
      students: rows.length,
      present: rows.filter(r => [...r.querySelectorAll(".s")].some(c => c.checked)).length,
      participated: rows.filter(r => [...r.querySelectorAll(".p")].some(c => c.checked)).length,
    };
    document.getElementById("total-students").textContent = totals.students;
    document.getElementById("students-present").textContent = totals.present;
    document.getElementById("students-participated").textContent = totals.participated;
    return totals;
  }

  document.getElementById("show-report-btn").addEventListener("click", () => {
    const totals = updateReport();
    const ctx = document.getElementById("report-chart").getContext("2d");
    const data = [totals.students, totals.present, totals.participated];

    if (!chart) {
      chart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Total", "Present ≥1", "Participated ≥1"],
          datasets: [{
            label: "Students",
            data,
            backgroundColor: ["#2563eb", "#10b981", "#f59e0b"]
          }]
        },
        options: {
          responsive: false,
          scales: { y: { beginAtZero: true } }
        }
      });
    } else {
      chart.data.datasets[0].data = data;
      chart.update();
    }
  });

  computeAll();
});