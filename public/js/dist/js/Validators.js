function Numbers(e) {
    var key = e.keyCode;
    if (key < 48 || key > 57) {
        e.preventDefault();
    }
}

function CleanText(data) {
    return data.replace(/(\r\n|\n|\r)/gm, ' ').replace(/['"`]/g, '');
}

function UpperCase(input) {
    input.value = input.value.toUpperCase();
}

function Trim(input) {
    input.value = input.value.trim();
}
