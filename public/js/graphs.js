Chart.defaults.global.defaultFontColor = 'black';
Chart.defaults.global.defaultFontFamily = 'Nunito Sans', sans-serif;


function stateOfThePartiesGraph(element, colours, labels, seats) {
    data = [50,2,2,2,2,2,2,2,2];
    new Chart(element, {
        "type": "horizontalBar",
        "data": {
            "labels": labels,
            "datasets": [{
                "data": seats,
                "fill": false,
                "backgroundColor": colours,
                "borderWidth": 1
            }]
        },
        "options": {
            "legend": {
                display: false
            },
            "scales": {
                "xAxes": [{
                    "ticks": {
                        "beginAtZero": true
                    }
                }]
            }
        }
    });
}

function voteShareGraph(element, colours, labels, votes) {
    data = [50,2,2,2,2,2,2,2,2];
    new Chart(element, {
        "type": "horizontalBar",
        "data": {
            "labels": labels,
            "datasets": [{
                "data": votes,
                "fill": false,
                "backgroundColor": colours,
                "borderWidth": 1
            }]
        },
        "options": {
            "legend": {
                display: false
            },
            "scales": {
                "xAxes": [{
                    "ticks": {
                        "beginAtZero": true
                    }
                }]
            }
        }
    });
}
