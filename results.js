// Functions for displaying estimate results
// Requires that jQuery be present

// Unit conversion matrix
// Use as follows: quantity *= conversion[want][have]
// Assumptions: 8 hours per day, 5 days per week, 4 weeks per month
var conversion = {
    'h': {'h': 1,       'd': 8,     'w': 8*5, 'm': 8*5*4},
    'd': {'h': 1/8,     'd': 1,     'w': 5,   'm': 5*4  },
    'w': {'h': 1/8/5,   'd': 1/5,   'w': 1,   'm': 4    },
    'm': {'h': 1/8/5/4, 'd': 1/5/4, 'w': 1/4, 'm': 1    }
};

// Entry point when data is retrieved. Only called once
function prepResults(data, div) {
    $.each(data.rounds, calcRange);
    displayResults(data, div);
}

// Normalizes the estimate to specific units
function normalize(estimate, unit) {
    estimate.low *= conversion[unit][estimate.low_unit];
    estimate.low_unit = unit;
    estimate.high *= conversion[unit][estimate.high_unit];
    estimate.high_unit = unit;
    return estimate;
}

// Displays the results in the given div
function displayResults(data, div) {
    div.html('');
    $.each(data.rounds, function() {
        round = this;
        div.append('<strong>Round ' + round.id + '</strong><br />');
        $.each(round.estimates, function() {
            estimate = this;
            div.append('<div class="numbers">'
                + estimate.low + ' ' + estimate.low_unit + ' -- '
                + estimate.high + ' ' + estimate.high_unit
                + '<div class="bar"><span class="bar1" style="width:'
                + estimate.low_percent + '%;">&nbsp;</span>'
                + '<span class="bar2" style="left:' + estimate.low_percent
                + '%;width:' + estimate.high_percent + '%;">&nbsp;</span></div>');
        });
        div.append('<hr />');
    });
}

var units = ['h', 'd', 'w', 'm'];
var normalization = 3;
function rotateNormalization() {
    normalization = (normalization + 1) % 4;
    $.each(results.rounds, function() {
        $.each(this.estimates, function() {
            estimate = this;
            estimate = normalize(estimate, units[normalization]);
        });
    });
    $("#normalize").html("Normalize (" + units[(normalization + 1) % 4] + ")");
    displayResults(results, $("div#results"));
}

function calcRange() {
    var low = 999; // magic number for infinity
    var high = 0;
    round = this;
    // Find min
    $.each(round.estimates, function() {
        var thislow = this.low * conversion['d'][this.low_unit];
        if (thislow < low) low = thislow;
    });
    // Find max
    $.each(round.estimates, function() {
        var thishigh = this.high * conversion['d'][this.high_unit];
        if (thishigh > high) high = thishigh;
    });
    // Calculate percentages
    $.each(round.estimates, function() {
        estimate = this;
        var thislow = this.low * conversion['d'][this.low_unit];
        var thishigh = this.high * conversion['d'][this.high_unit];
        var templow = thislow - low;
        var temphigh = thishigh;
        estimate.low_percent = templow / high * 100;
        estimate.high_percent = temphigh / high * 100 - estimate.low_percent;
    });
}
