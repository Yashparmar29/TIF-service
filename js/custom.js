// to get current year
function getYear() {
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    document.querySelector("#displayYear").innerHTML = currentYear;
}

getYear();

// toggle overlay menu
function openNav() {
    document.getElementById("myNav").classList.toggle("menu_width");
    document.querySelector(".custom_menu-btn").classList.toggle("menu_btn-style");
}

// nice select
$(document).ready(function () {
    $('select').niceSelect();

    // search functionality
    if ($('.food-card').length > 0) {
        function performSearch() {
            var searchTerm = $('.form-inline input[type="search"]').val().toLowerCase();
            $('.food-card').each(function() {
                var title = $(this).find('h5').text().toLowerCase();
                var description = $(this).find('p').text().toLowerCase();
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('.form-inline input[type="search"]').on('input', performSearch);

        $('.form-inline .nav_search-btn').on('click', function(e) {
            e.preventDefault();
            performSearch();
        });

        // prevent form submission
        $('.form-inline').on('submit', function(e) {
            e.preventDefault();
        });
    }
});

// slick slider

$(".slider_container").slick({
    autoplay: true,
    autoplaySpeed: 10000,
    speed: 600,
    slidesToShow: 4,
    slidesToScroll: 1,
    pauseOnHover: false,
    draggable: false,
    prevArrow: '<button class="slick-prev"> </button>',
    nextArrow: '<button class="slick-next"></button>',
    responsive: [{
            breakpoint: 991,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                adaptiveHeight: true,
            },
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
            },
        },
        {
            breakpoint: 576,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
            },
        },
        {
            breakpoint: 420,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        }
    ]
});