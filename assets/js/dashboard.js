$(document).ready(function() {
    // Mobile menu toggle
    $('.mobile-menu-toggle, .close-sidebar').on('click', function() {
        $('.sidebar').toggleClass('active');
    });

    // Initialize charts
    initCharts();
    
    // Load stats data
    loadStats();
    
    // Load recent activity
    loadRecentActivity();
    
    // Global search functionality
    $('#global-search').on('keyup', function(e) {
        if (e.key === 'Enter') {
            const query = $(this).val().trim();
            if (query) {
                window.location.href = `employees.html?search=${encodeURIComponent(query)}`;
            }
        }
    });
    
    // Notification dropdown (would be implemented with actual notifications)
    $('.header-notifications').on('click', function() {
        alert('Notifications would open here');
    });
    
    // User profile dropdown (would be implemented)
    $('.header-user').on('click', function() {
        alert('User menu would open here');
    });
});

function initCharts() {
    // Employee Growth Chart
    const growthCtx = document.getElementById('employee-growth-chart').getContext('2d');
    const growthChart = new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Employees Hired',
                data: [12, 19, 15, 25, 18, 30],
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                borderColor: 'rgba(67, 97, 238, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Department Distribution Chart
    const deptCtx = document.getElementById('department-distribution-chart').getContext('2d');
    const deptChart = new Chart(deptCtx, {
        type: 'doughnut',
        data: {
            labels: ['Marketing', 'Sales', 'IT', 'HR', 'Operations'],
            datasets: [{
                data: [15, 22, 18, 12, 8],
                backgroundColor: [
                    '#4361ee',
                    '#3f37c9',
                    '#4cc9f0',
                    '#4895ef',
                    '#560bad'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            },
            cutout: '70%'
        }
    });
}

function loadStats() {
    // Simulate API call with setTimeout
    setTimeout(() => {
        $('#total-employees').text('142');
        $('#total-departments').text('8');
        $('#on-leave').text('9');
        $('#productivity-score').text('87%');
    }, 800);
}

function loadRecentActivity() {
    const activities = [
        {
            icon: 'fa-user-plus',
            text: 'New employee John Doe added to Marketing department',
            time: '2 hours ago'
        },
        {
            icon: 'fa-user-edit',
            text: 'Employee Jane Smith updated her profile information',
            time: '5 hours ago'
        },
        {
            icon: 'fa-building',
            text: 'Marketing department budget increased to $750,000',
            time: '1 day ago'
        },
        {
            icon: 'fa-calendar-check',
            text: 'Company-wide meeting scheduled for Friday',
            time: '2 days ago'
        }
    ];

    const $activityList = $('.activity-list');
    $activityList.empty();

    activities.forEach(activity => {
        $activityList.append(`
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas ${activity.icon}"></i>
                </div>
                <div class="activity-details">
                    <p>${activity.text}</p>
                    <span class="activity-time">${activity.time}</span>
                </div>
            </div>
        `);
    });
}

// Tooltip initialization
$(document).on('mouseenter', '[data-tooltip]', function() {
    const tooltipText = $(this).data('tooltip');
    const $tooltip = $(`<div class="tooltip">${tooltipText}</div>`);
    
    $('body').append($tooltip);
    const rect = this.getBoundingClientRect();
    
    $tooltip.css({
        left: rect.left + rect.width / 2 - $tooltip.outerWidth() / 2,
        top: rect.top - $tooltip.outerHeight() - 5
    });
    
    $(this).data('tooltip-element', $tooltip);
}).on('mouseleave', '[data-tooltip]', function() {
    const $tooltip = $(this).data('tooltip-element');
    if ($tooltip) {
        $tooltip.remove();
    }
});