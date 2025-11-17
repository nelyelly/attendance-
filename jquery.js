$(document).ready(function () {
    let flashing = null;
    let currentSort = "None";

    // --- EXO 5: Hover highlight and click info ---
    
    // Hover effects using event delegation for dynamic rows
    $(document).on("mouseenter", ".student-row", function () {
        $(this).css("background-color", "#e0e7ff");
    });
    
    $(document).on("mouseleave", ".student-row", function () {
        $(this).css("background-color", "");
    });

    // Click to show student info - ONLY when clicking name cells (not checkboxes)
    $(document).on("click", ".sid, .lname, .fname", function () {
        const row = $(this).closest(".student-row");
        const fname = row.find(".fname").text();
        const lname = row.find(".lname").text();
        const abs = row.find(".absences").text();
        alert("Student: " + fname + " " + lname + "\nAbsences: " + abs);
    });

    // --- EXO 6: Highlight Excellent Students ---

    // Stop Flashing function
    function stopFlashing() {
        if (flashing) {
            clearInterval(flashing);
            flashing = null;
        }
        $(".student-row").css("opacity", "1");
    }

    // Highlight Excellent Students button
    $("#highlight-excellent").on("click", function () {
        stopFlashing();

        // Find students with fewer than 3 absences
        const goodRows = $(".student-row").filter(function () {
            const abs = parseInt($(this).find(".absences").text(), 10);
            return !Number.isNaN(abs) && abs < 3;
        });

        if (goodRows.length === 0) {
            alert("No excellent students yet!");
            return;
        }

        // Animate rows with fade in/out effect
        let faded = false;
        flashing = setInterval(function () {
            faded = !faded;
            goodRows.css("opacity", faded ? "0.4" : "1");
        }, 600);
    });

    // Reset Colors button
    $("#reset-colors").on("click", function () {
        stopFlashing();
        $(".student-row").stop(true, true).css({
            "opacity": "1",
            "background-color": ""
        });
    });

    // --- EXO 7: Search and Sort Features ---

    // Search by Name functionality
    $("#search-name").on("input", function () {
        const searchTerm = $(this).val().toLowerCase().trim();
        
        if (searchTerm === "") {
            // Show all rows if search is empty
            $(".student-row").show();
        } else {
            // Filter rows based on first name or last name
            $(".student-row").each(function () {
                const firstName = $(this).find(".fname").text().toLowerCase();
                const lastName = $(this).find(".lname").text().toLowerCase();
                
                const matches = firstName.includes(searchTerm) || lastName.includes(searchTerm);
                $(this).toggle(matches);
            });
        }
    });

    // Sort by Absences (Ascending)
    $("#sort-absences").on("click", function () {
        const rows = $(".student-row").get();
        
        rows.sort(function(a, b) {
            const absA = parseInt($(a).find(".absences").text(), 10);
            const absB = parseInt($(b).find(".absences").text(), 10);
            return absA - absB; // Ascending order
        });
        
        // Re-append sorted rows
        $.each(rows, function(index, row) {
            $("#attendance-table tbody").append(row);
        });
        
        currentSort = "absences (ascending)";
        updateSortMessage();
    });
// Sort by Participation (Descending)
    $("#sort-participation").on("click", function () {
        const rows = $(".student-row").get();
        
        rows.sort(function(a, b) {
            const partA = parseInt($(a).find(".participation-count").text(), 10);
            const partB = parseInt($(b).find(".participation-count").text(), 10);
            return partB - partA; // Descending order
        });
        
        // Re-append sorted rows
        $.each(rows, function(index, row) {
            $("#attendance-table tbody").append(row);
        });
        
        currentSort = "participation (descending)";
        updateSortMessage();
    });

    // Update sort message
    function updateSortMessage() {
        $("#sort-message").text("Currently sorted by: " + currentSort);
    }

    // Initialize sort message
    updateSortMessage();
});