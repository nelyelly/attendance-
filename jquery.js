$(document).ready(function () {
    // Remove any existing modal
    $('#student-modal').remove();
    
    // Create modal with SIMPLE styling
    $('body').append(`
        <div id="student-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(45, 90, 77, 0.5); z-index: 9999;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; border: 2px solid #ffb6c1; max-width: 400px; width: 90%;">
                <span class="close-modal" style="position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: #2d5a4d;">&times;</span>
                <h3 style="color: #2d5a4d; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #ffb6c1; padding-bottom: 10px;">Student Information</h3>
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0d4dc;">
                        <span style="font-weight: 600;">Name:</span>
                        <span id="modal-name" style="color: #2d5a4d; font-weight: 500;"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                        <span style="font-weight: 600;">Absences:</span>
                        <span id="modal-absences" style="color: #2d5a4d; font-weight: 500;"></span>
                    </div>
                </div>
                <button class="modal-ok-btn" style="background: #4a9b82; color: white; border: none; border-radius: 6px; padding: 10px 25px; font-weight: 600; cursor: pointer; display: block; margin: 0 auto;">OK</button>
            </div>
        </div>
    `);

    // Click handler
    $(document).on("click", ".fname, .lname, .sid", function () {
        const row = $(this).closest(".student-row");
        const fname = row.find(".fname").text().trim();
        const lname = row.find(".lname").text().trim();
        const abs = row.find(".absences").text().trim();
        
        $('#modal-name').text(fname + ' ' + lname);
        $('#modal-absences').text(abs + ' absences');
        $('#student-modal').show();
    });

    // Close modal
    $(document).on('click', '.close-modal, .modal-ok-btn', function() {
        $('#student-modal').hide();
    });

    $(document).on('click', function(e) {
        if ($(e.target).is('#student-modal')) {
            $('#student-modal').hide();
        }
    });

    // Rest of your functions...
    let flashing = null;
    let currentSort = "None";

    $(document).on("mouseenter", ".student-row", function () {
        $(this).css("background-color", "#e0e7ff");
    });
    
    $(document).on("mouseleave", ".student-row", function () {
        $(this).css("background-color", "");
    });

    function stopFlashing() {
        if (flashing) {
            clearInterval(flashing);
            flashing = null;
        }
        $(".student-row").css("opacity", "1");
    }

    $("#highlight-excellent").on("click", function () {
        stopFlashing();
        const goodRows = $(".student-row").filter(function () {
            const abs = parseInt($(this).find(".absences").text(), 10);
            return !Number.isNaN(abs) && abs < 3;
        });

        if (goodRows.length === 0) {
            alert("No excellent students yet!");
            return;
        }

        let faded = false;
        flashing = setInterval(function () {
            faded = !faded;
            goodRows.css("opacity", faded ? "0.4" : "1");
        }, 600);
    });

    $("#reset-colors").on("click", function () {
        stopFlashing();
        $(".student-row").css({
            "opacity": "1",
            "background-color": ""
        });
    });

    $("#search-name").on("input", function () {
        const searchTerm = $(this).val().toLowerCase().trim();
        $(".student-row").each(function () {
            const firstName = $(this).find(".fname").text().toLowerCase();
            const lastName = $(this).find(".lname").text().toLowerCase();
            const matches = firstName.includes(searchTerm) || lastName.includes(searchTerm);
            $(this).toggle(matches);
        });
    });

    $("#sort-absences").on("click", function () {
        const rows = $(".student-row").get();
        rows.sort(function(a, b) {
            const absA = parseInt($(a).find(".absences").text(), 10) || 0;
            const absB = parseInt($(b).find(".absences").text(), 10) || 0;
            return absA - absB;
        });
        $.each(rows, function(index, row) {
            $("#attendance-table tbody").append(row);
        });
        currentSort = "absences (ascending)";
        updateSortMessage();
    });

    $("#sort-participation").on("click", function () {
        const rows = $(".student-row").get();
        rows.sort(function(a, b) {
            const partA = parseInt($(a).find(".participation-count").text(), 10) || 0;
            const partB = parseInt($(b).find(".participation-count").text(), 10) || 0;
            return partB - partA;
        });
        $.each(rows, function(index, row) {
            $("#attendance-table tbody").append(row);
        });
        currentSort = "participation (descending)";
        updateSortMessage();
    });

    function updateSortMessage() {
        $("#sort-message").text("Currently sorted by: " + currentSort);
    }
    updateSortMessage();
});