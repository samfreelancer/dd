function existsEmptyCustomReadingText() {
    return $(".customReadingText").filter(function() {
        return this.value === "";
    }).length > 0;
}

function removeNonActiveEmptyCustomReadingTexts() {
    if ($('.customReadingText').length > 1){
        $(".customReadingText:disabled").filter(function() {
            return this.value === "";
        }).parents(".checkbox").remove();
        reorderCustomReadingTexts();
    }
}

function addNewEmptyCustomReadingText() {
    var columns = $('.readingsColumn');
    var columnsWithCounts = [];

    for (var i = 0; i < columns.length; ++i) {
        var column = $(columns[i]);
        columnsWithCounts.push({
            'count': column.find('.checkbox').length,
            'column': column
        });
    }

    var minCount = columnsWithCounts[0].count;
    var minColumn = columnsWithCounts[0].column;

    for (var i = 1; i < columns.length; ++i) {
        if (columnsWithCounts[i].count < minCount) {
            minCount = columnsWithCounts[i].count;
            minColumn = columnsWithCounts[i].column;
        }
    }

    minColumn.append($('#customReadingTextTemplate').html());
    
    App.handleUniform();
    setCustomReadingEvents();
}

function reorderCustomReadingTexts() {
    var columns = $('.readingsColumn');
    var columnsWithCounts = [];
    var allCustomReadingTextsCount = 0;
    
    for (var i = 0; i < columns.length; ++i) {
        var column = $(columns[i]);
        var countInColumn = column.find('.checkbox').length;
        allCustomReadingTextsCount += countInColumn;
        
        columnsWithCounts.push({
            'count': countInColumn,
            'column': column
        });
    }
    
    var numberOfRowsPerColumn = allCustomReadingTextsCount / columns.length;
    
    var textsToRelocate = [];
    for(var i = 0; i < columns.length; ++i){
        if (columnsWithCounts[i].count > Math.ceil(numberOfRowsPerColumn)){
            var texts = columnsWithCounts[i].column.find('.checkbox');
            for (var j = texts.length - 1; j > 0 && j + 1 > Math.ceil(numberOfRowsPerColumn); --j){
                textsToRelocate.push(texts[j]);
            }
        }
    }
    
    for (var i = 0; i < columns.length; ++i){
        if (columnsWithCounts[i].count < Math.ceil(numberOfRowsPerColumn)){
            for (var j = 0; j < columnsWithCounts[j].count - Math.ceil(numberOfRowsPerColumn); ++j){
                $(textsToRelocate.pop()).appendTo(columnsWithCounts[i].column);
            }
        }
    }
}

function setCustomReadingEvents(){
    $('.customReadingCheckbox:not(.processed)').click(function() {
        var $this = $(this);
        if ($this.is(':checked')) {
            $this.parents('.customReadingsContainer').find('.customReadingText').removeAttr('disabled');
        } else {
            $this.parents('.customReadingsContainer').find('.customReadingText').val('').attr('disabled', 'disabled');
            removeNonActiveEmptyCustomReadingTexts();
        }
    });

    $('.customReadingText:not(.processed)').keyup(function() {
        if (this.value === "") {
            removeNonActiveEmptyCustomReadingTexts();
        } else {
            if (!existsEmptyCustomReadingText()) {
                addNewEmptyCustomReadingText();
            }
        }
    });
    
    $('.customReadingCheckbox').addClass('processed');
    $('.customReadingText').addClass('processed');
}

$(function() {
    setCustomReadingEvents();
    $("#phone_number").inputmask("mask", {"mask": "999-999-9999"});
});