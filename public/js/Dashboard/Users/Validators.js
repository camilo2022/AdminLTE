function Numbers(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}

function CleanText(data) {
    return data.replace(/(\r\n|\n|\r)/gm, ' ').replace(/['"`]/g, '');
}
