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
            div.append(estimate.low + ' ' + estimate.low_unit + ' -- '
                + estimate.high + ' ' + estimate.high_unit + '<br />');
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
