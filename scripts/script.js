document.getElementById('searchForm').onsubmit = function(event) {
    event.preventDefault();
    const searchInput = document.getElementById('searchInput').value;

    const formAction = document.getElementById('searchForm').action;
    window.location.href = `${formAction}?term=${encodeURIComponent(searchInput)}`;
};
