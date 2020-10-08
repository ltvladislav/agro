window.Livlag = {
    Enums: {
        ExpressionType: {
            AND: 1,
            OR: 2
        },
        DataValueType: {
            Integer: 1,
            Decimal: 2,
            Boolean: 3,
            Text: 4,
            Varchar: 5,
            Lookup: 6,
            Guid: 7,

            Undefined: -1
        },
        ConditionType: {
            Equal: 1,
            NotEqual: 2,
            More: 3,
            MoreEqual: 4,
            Less: 5,
            LessEqual: 6
        },
        DomType: {
            Input: 1,
            Select: 2
        },
        OrderDirection: {
            ASC: 1,
            DESC: 2
        }
    },

    GetOrderDirection: function(currentDirection) {
        return currentDirection && currentDirection == Livlag.Enums.OrderDirection.ASC ?
            Livlag.Enums.OrderDirection.DESC :
            Livlag.Enums.OrderDirection.ASC;
    },

    GetConditionSelectOptions: function() {
        return [
            {
                value: 1,
                text: "="
            },
            {
                value: 2,
                text: "<>"
            },
            {
                value: 3,
                text: ">"
            },
            {
                value: 4,
                text: ">="
            },
            {
                value: 5,
                text: "<"
            },
            {
                value: 6,
                text: "<="
            }
        ];
    },

    GetValueDomTypeForValueType: function(valueType) {
        return valueType == Livlag.Enums.DataValueType.Lookup ?
            Livlag.Enums.DomType.Select : Livlag.Enums.DomType.Input;
    },
    CallInChain: function(array, scope) {
        let wrapper = function(target, argFn) {
            return function() {
                target.call(scope, argFn, scope);
            }
        }
        let lastFn = array[array.length-1];
        array[array.length-1] = function() {
            lastFn.call(scope);
        }
        for (let i = array.length-1; i > 0; i--) {
            array[i-1] = wrapper(array[i-1], array[i]);
        }
        array[0]();
    },
    CallService: function(serviceName, config, callback, scope) {
        Helper.callService(serviceName, config, callback, scope);
    },
    CallDbService: function(config, callback, scope) {
        Livlag.CallService("tables/database", config, callback, scope);
    },
    Callback: function(callback, args, scope) {
        Helper.callback(callback, args, scope);
    },
    NewGuid: function() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    },
    emptyFn: function() {

    },
    IsEmpty: function(value) {
        if (value instanceof Object) {
            for(let key in value) {
                if (value[key]) {
                    return false;
                }
            }
            return true;
        }
        return false;
    },
    Equals: function(a, b) {
        if (a === b) {
            return true;
        }
        if (a instanceof Object && b instanceof Object) {
            let keys = [];
            for(let key in a) {
                keys.push(key);
                if (!Livlag.Equals(a[key], b[key])) {
                    return false;
                }
            }
            for(let key in b) {
                if (keys.includes(key)) {
                    continue;
                }
                if (!Livlag.Equals(a[key], b[key])) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}


export class TableStructureHelper {
    static loadStructure(schemaName, callback, scope) {
        Livlag.CallService("tables/tablesStructures", {
            schemaName: schemaName
        }, function(result) {
            if (result.success) {

                Livlag.Callback(callback, result.result.columns, scope);
                return;
            }
            Livlag.Callback(callback, result, scope);
        }, this)
    }
    static loadTextColumnValues(schemaName, columnName, first, callback, scope) {
        Livlag.CallDbService({
            query: `SELECT DISTINCT ${columnName} FROM ${schemaName} WHERE ${columnName} LIKE '%${first}%' LIMIT 50`
        }, function(result) {
            if (result.success) {
                let values = [];
                result.collection.forEach(function(col) {
                    values.push(col[columnName]);
                })
                Livlag.Callback(callback, [values], scope);
                return;
            }
            Livlag.Callback(callback, [], scope);
        }, this)
    }
}
export default class TableViewModel {
    constructor(structure) {
        this.structure = structure;

        if (this.structure.initColumnsOnly) {
            Livlag.CallInChain([
                this.initColumns,
                function() {
                    let scope = this.structure.initColumnsScope || this;
                    Livlag.Callback(this.structure.initColumnsCallback, null, scope);
                }
            ], this);
        }
        else {
            Livlag.CallInChain([
                this.initColumns,
                this.init
            ], this);
        }
    }
    init(initCallback, initScope) {
        if (this.isInitialized) {
            Livlag.CallInChain([
                this.loadTable,
                function(callback, scope) {
                    Livlag.Callback(callback, null, scope);
                    Livlag.Callback(initCallback, null, initScope);
                }
            ], this);
        }
        else {
            Livlag.CallInChain([
                function(callback, scope) {
                    this.generate();
                    this.loadFilter();
                    this.loadPagination();
                    Livlag.Callback(callback, null, scope);
                },
                this.loadTable,
                function(callback, scope) {
                    this.isInitialized = true;
                    Livlag.Callback(callback, null, scope);
                    Livlag.Callback(initCallback, null, initScope);
                }
            ], this);
        }
    }
    get schemaName() {
        return this.structure && this.structure.schemaName || "";
    }
    initColumns(callback, scope) {
        TableStructureHelper.loadStructure(this.schemaName, function(columns) {
            this.columns = columns;
            Livlag.Callback(callback, null, scope);
        }, this);
    }
    loadFilter(callback, scope) {
        if (!this.filter) {
            this.filter = new FilterViewModel({
                parentObject: this,
                container: this.htmlElementFilter
            });
        }
        this.filter.generate();
        Livlag.Callback(callback, null, scope);
    }
    loadPagination(callback, scope) {
        if (!this.pagination) {
            this.pagination = new PaginationViewModel({
                parentObject: this,
                container: this.htmlElementPagination
            });
        }
        this.pagination.generate();
        Livlag.Callback(callback, null, scope);
    }
    loadSort(callback, scope) {
        if (!this.sort) {
            this.sort = new SortViewModel({
                parentObject: this
            });
        }
        this.sort.init();
        Livlag.Callback(callback, null, scope);
    }

    getColumnNames() {
        var keys = [];
        for(var key in this.columns) {
            if (this.columns[key].selectEnabled !== false) {
                keys.push({
                    value: key,
                    caption: this.columns[key].caption
                });
            }
        }
        return keys;
    }
    getColumnValues(columnName, search, callback, scope) {
        TableStructureHelper.loadTextColumnValues(this.schemaName, columnName, search, callback, scope);
    }
    isFromPagination() {

    }
    loadTable(callback, scope) {
        var config = {
            schemaName: this.schemaName,
            columns: this.columns,
            startPosition: 0,
            rowCount: 30
        };

        let filter = this.filter && this.filter.getFilter();
        if (filter){
            config.filter = filter;
        }

        let pagination = this.pagination && this.pagination.getConfig() || {};
        config.rowCount = pagination.rowCount || 30;
        config.startPosition = pagination.startPosition || 0;

        Livlag.CallDbService(config, function(result) {
            if (result.success) {
                this.fullRowCount = +result.count;
                this.printTable(result.collection);
                this.onTableLoad();
                Livlag.Callback(callback, null, scope);
            }
            else {
                console.error(result);
            }
        }, this);
    }
    onTableLoad() {
        this.loadSort();

        if (this.pagination.isReload !== false) {
            this.pagination.pageNumber = 1;
            this.pagination.reload();
        }
    }
    printTable(collection) {
        if (this.htmlElementTable[0]) {
            this.htmlElementTable.html(this.getHTMLTable(collection));
        }
    }

    generate(callback, scope) {
        this.htmlElement = $("#" + this.structure.generateTo);
        if (!this.htmlElementFilter) {
            this.htmlElementFilter = $("<div/>", {
                id: this.structure.generateTo + "-filter",
                class: "filter-module-container"
            });
            this.htmlElement.append(this.htmlElementFilter);
        }
        if (!this.htmlElementTable) {
            this.htmlElementTable = $("<div/>", {
                id: this.structure.generateTo + "-tbl",
                class: "table-id"
            });
            this.htmlElement.append(this.htmlElementTable);
        }
        if (!this.htmlElementPagination) {
            this.htmlElementPagination = $("<div/>", {
                id: this.structure.generateTo + "-pagination",
                class: "pagination"
            });
            this.htmlElement.append(this.htmlElementPagination);
        }
        Livlag.Callback(callback, null, scope);
    }

    getHTMLTable(data) {
        let cssClass = this.structure.cssClass ? (" " + this.structure.cssClass) : "";
        var output = `<table class="${cssClass}">`;
        output += '<thead><tr>';
        for(var key in this.columns) {
            if (this.columns[key].visible !== false) {

            output += '<th id="' + this.schemaName + key + '"><div class="table-cell"><strong>' + this.columns[key].caption || key + '</strong></div></th>';
            }
        }
        output += '</tr></thead><tbody>';
        for (var i = 0; i < data.length; i++) {
            output += '<tr>';
            for (let key in data[i]) {
                output += '<td>' + data[i][key] + '</td>';
            }
            output += '</tr>';
        }
        output += '</tbody></table>';
        return output;
    }


    getRowCount(callback, scope) {
        let config = {
            schemaName: this.schemaName,
            count: true
        };
        let filter = this.filter && this.filter.getFilter();
        if (filter){
            config.filter = filter;
        }
        Livlag.CallDbService(config, function(result) {
            if (result.success) {
                this.fullRowCount = +result.count;
                Livlag.Callback(callback, [this.fullRowCount], scope);
            }
            else {
                console.error(result);
                Livlag.Callback(callback, [], scope);
            }
        }, this);
    }

    resetPagination() {
        this.pagination.pageNumber = 1;
    }
}



export class FilterViewModel {
    constructor(config) {
        this.filter = {
            collection: {},
            expressionType: Livlag.Enums.ExpressionType.AND
        };
        this.config = config;
    }
    get parent() {
        return this.config.parentObject;
    }
    get schemaName() {
        return (this.parent && this.parent.schemaName) || "";
    }
    get container() {
        return this.config.container;
    }

    generate() {
        if (!this.container) {
            return;
        }
        this.container.append(this.getAddFilterButton());
    }
    getAddFilterButton() {
        var scope = this;
        return $('<button/>', {
            html: "Добавить фильтр",
            'click': function() {
                Livlag.Callback(scope.addNewFilterItemElement, null, scope);
            }
        });
    }

    getColumnValues(columnName, search, callback, scope) {
        this.parent.getColumnValues(columnName, search, callback, scope);
    }
    addNewFilterItemElement() {
        if (!this.filterItemsContainer) {
            this.filterItemsContainer = $('<div/>', {
                id: 'filter-container-' + Livlag.NewGuid(),
                class: "filter-container"
            });
            this.container.append(this.filterItemsContainer);
        }

        let filterId = 'filter-' + Livlag.NewGuid();
        let filterItem = this.getFilterItemById(filterId);
        let div = $('<div/>', {
            id: filterId,
            class: 'filter-item'
        });
        filterItem.container = div;
        this.filterItemsContainer.append(div);
        this.initColumnSelect(filterId);
    }
    getFilterItemById(filterId) {
        return this.filter.collection[filterId] || (this.filter.collection[filterId] = {});
    }

    initColumnSelect(filterId) {
        let filterItem = this.getFilterItemById(filterId);

        let columnId = filterId + '-column';
        let select = $('<select/>', {
            id: columnId,
            class: 'select-column'
        });

        filterItem.selectColumn = select;
        filterItem.container.append(select);

        let scope = this;
        let options = [];
        options.push({
            'data-placeholder': true,
            text: 'Оберіть колонку'
        });
        this.parent.getColumnNames().forEach(function(colName) {
            options.push({
                text: colName.caption,
                value: colName.value
            });
        });
        filterItem.selectColumnSlim = new SlimSelect({
            select: "#" + filterId + '-column',
            data: options,
            placeholder: 'Оберіть колонку',
            onChange: function(info) {
                Livlag.Callback(scope.onColumnSelectChanged, [filterId, info.value], scope);
            }
        });
    }
    onColumnSelectChanged(filterId, column) {
        let filterItem = this.getFilterItemById(filterId);
        filterItem.columnName = column;
        this.initConditionSelect(filterId);
        this.initValueDom(filterId);
        this.initRemoveBtn(filterId);
    }
    initConditionSelect(filterId) {
        let filterItem = this.getFilterItemById(filterId);
        if (filterItem.selectCondition) {
            return;
        }
        let conditionId = filterId + '-condition';
        let select = $('<select/>', {
            id: conditionId,
            class: 'select-condition'
        });

        filterItem.selectCondition = select;

        let div = filterItem.container.append(select);
        //
        let scope = this;
        filterItem.selectConditionSlim = new SlimSelect({
            select: "#" + conditionId,
            data: Livlag.GetConditionSelectOptions(),
            placeholder: "Тип порівняння",
            showSearch: false,
            onChange: function(info) {
                Livlag.Callback(scope.onConditionTypeChanged, [filterId, info.value], scope);
            }
        });
    }
    onConditionTypeChanged(filterId, conditionValue) {
        let filterItem = this.getFilterItemById(filterId);
        conditionValue = Number.parseInt(conditionValue);
        filterItem.conditionType = conditionValue;
    }
    initRemoveBtn(filterId) {
        let filterItem = this.getFilterItemById(filterId);
        if (filterItem.removeBtn) {
            return;
        }
        let scope = this;

        filterItem.removeBtn = $('<a/>', {
            id: filterId + '-remove',
            class: 'remove-filter-button',
            html: "<svg  xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\"\n" +
                "\t  viewBox=\"0 0 357 357\" style=\"enable-background:new 0 0 357 357;\" >\n" +
                "\t<polygon points=\"357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3 \n" +
                "\t\t\t214.2,178.5\"/>\n" +
                "</svg>",
            click: function() {
                Livlag.Callback(scope.removeFilterItem, [filterId], scope);
            }
        });
        filterItem.container.append(filterItem.removeBtn);
    }

    removeFilterItem(filterId) {
        let filterItem = this.getFilterItemById(filterId);
        if (filterItem.selectValue) {
            filterItem.selectValueSlim.destroy();
            filterItem.selectValueSlim = null;
            filterItem.selectValue[0].remove();
            filterItem.selectValue = null;
        }
        if (filterItem.inputValue) {
            filterItem.inputValue[0].remove();
            filterItem.inputValue = null;
        }
        filterItem.selectColumnSlim.destroy();
        filterItem.selectColumnSlim = null;
        filterItem.selectColumn[0].remove();
        filterItem.selectColumn = null;

        filterItem.selectConditionSlim.destroy();
        filterItem.selectConditionSlim = null;
        filterItem.selectCondition[0].remove();
        filterItem.selectCondition = null;

        filterItem.removeBtn[0].remove();
        filterItem.removeBtn = null;


        filterItem.container[0].remove();
        filterItem.container = null;

        this.filter.collection[filterId] = null;
        this.initOkFilterButton();
        this.parent.loadTable();
    }
    initOkFilterButton() {
        if (Livlag.IsEmpty(this.filter.collection)) {
            this.okFilterButton[0].remove();
            this.okFilterButton = null;
            return;
        }
        if (this.okFilterButton) {
            return;
        }
        var scope = this;
        this.okFilterButton = $('<button/>', {
            html: "Применить",
            'click': function() {
                Livlag.Callback(scope.parent.loadTable, null, scope.parent);
            }
        });
        this.container.append(this.okFilterButton);
    }
    initValueDom(filterId) {
        let filterItem = this.getFilterItemById(filterId);
        let dataValueType = this.parent.columns[filterItem.columnName].dataValueType;
        let domType = Livlag.GetValueDomTypeForValueType(dataValueType);
        if (domType === Livlag.Enums.DomType.Select) {
            this.initValueSelect(filterId);
        }
        else {
            this.initValueInput(filterId);
        }
    }
    initValueSelect(filterId) {
        let filterItem = this.getFilterItemById(filterId);
        let scope = this;

        if (filterItem.inputValue) {
            filterItem.inputValue[0].remove();
            filterItem.inputValue = null;
        }

        let valSelectId = filterId + '-value'

        if (!filterItem.selectValue) {
            let select = $('<select/>', {
                id: valSelectId,
                class: 'select-value'
            });
            filterItem.selectValue = select;
            filterItem.container.append(select);
        }

        filterItem.selectValueSlim = new SlimSelect({
            select: '#' + valSelectId,
            searchingText: 'Поиск...', // Optional - Will show during ajax request
            ajax: function (search, callback) {
                Livlag.Callback(scope.getColumnValues, [column, search, function(values) {
                    callback(values.map((el) => {
                        return {
                            text: el,
                            value: el
                        };
                    }));
                }, scope], scope);
            },
            onChange: function(info) {
                Livlag.Callback(scope.onColumnValueChange, [filterId, info.value], scope);
            }
        });
    }
    initValueInput(filterId) {
        let filterItem = this.getFilterItemById(filterId);
        let scope = this;
        if (filterItem.selectValue) {
            filterItem.selectValueSlim.destroy();
            filterItem.selectValueSlim = null;
            filterItem.selectValue[0].remove();
            filterItem.selectValue = null;
        }
        let valInputId = filterId + '-value'

        if (!filterItem.inputValue) {
            let input = $('<input/>', {
                id: valInputId,
                type: "text"
            });
            filterItem.inputValue = input;
            input.on("change", function() {
                Livlag.Callback(scope.onColumnValueChange, [filterId, input.val()], scope);
            });
            filterItem.container.append(input);
        }
        filterItem.inputValue.val("");
    }
    onColumnValueChange(filterId, value) {
        let filterItem = this.getFilterItemById(filterId);
        filterItem.columnValue = value;
        this.initOkFilterButton();
    }
    getFilter() {
        let filter = this.filter;
        if (filter && filter.collection && !Livlag.IsEmpty(filter.collection)) {
            let filterConfig = {
                collection: {},
                expressionType: filter.expressionType
            };

            for (let fkey in filter.collection) {
                let filterItem = filter.collection[fkey];
                if (!filterItem || !filterItem.columnName || !filterItem.columnValue || !filterItem.conditionType) {
                    continue;
                }
                filterConfig.collection[fkey] = {
                    columnName: filterItem.columnName,
                    columnValue: filterItem.columnValue,
                    conditionType: filterItem.conditionType
                };
            }
            return !Livlag.IsEmpty(filterConfig.collection) ? filterConfig : null;
        }
        return null;
    }
}

export class PaginationViewModel {
    constructor(config) {
        this.config = config;
        this.rowCount = 25;
        this.pageNumber = 1;
    }
    get container() {
        return this.config.container;
    }
    get parent() {
        return this.config.parentObject;
    }
    generate() {
        if (!this.container) {
            return;
        }
        this.initRowCountSelect();
        this.initButtons();
    }
    initPageCount() {
        let rowCount = this.parent.fullRowCount || 10;
        this.PageCount = rowCount / this.rowCount;
        if (this.PageCount % 1 > 0) {
            this.PageCount = this.PageCount - this.PageCount % 1 + 1
        }
    }
    initRowCountSelect() {
        this.buttonContainer = $('<div/>', {
            id: this.parent.schemaName + "-button-container",
            class: "pagination-wrap"
        });
        this.container.append(this.buttonContainer);

        let selectId = this.parent.schemaName + 'select-pagination-row-count';
        let select = $('<select/>', {
            id: selectId,
            class: 'pagination-row-count-select'
        });
        let label = $('<label/>', {
            id: selectId + "-label",
            class: 'pagination-row-count-select-label',
            text: "Кількість записів на сторінці : "
        });

        label.append(select);
        this.container.append(label);

        let scope = this;
        let options = [10, 25, 50, 100].map((i) => {
            return {
                text: i,
                value: i
            }
        });
        new SlimSelect({
            select: "#" + selectId,
            data: options,
            showSearch: false,
            onChange: function(info) {
                Livlag.Callback(scope.setRowCount, [info.value], scope);
            }
        });
        $("#" + selectId).val(25);
    }
    initButtons() {
        let id = this.parent.schemaName;
        let getBtn = function(config) {
            return $('<a/>', {
                id: id + "-" + config.class,
                class: config.class,
                text: config.text,
                html: config.html,
                click: config.callback
            });
        }
        let scope = this;

        this.buttonContainer.append(getBtn({
            class: "pagination-prev-button",
            html: "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\"\n" +
                "\t viewBox=\"0 0 477.175 477.175\"  >\n" +
                "\t<path d=\"M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225\n" +
                "\t\tc2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z\"/>\n" +
                "</svg>",
            callback: function() {
                Livlag.Callback(scope.setPage, [scope.pageNumber - 1], scope);
            },

        }));

        this.numberContainer = $('<div/>', {
            id: this.parent.schemaName + "-number-container",
            class: "pagination-links"
        });
        this.buttonContainer.append(this.numberContainer);

        this.buttonContainer.append(getBtn({
            class: "pagination-next-button",
            html: "<svg  xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\"\n" +
                "\t viewBox=\"0 0 477.175 477.175\" >\n" +
                "\t<path d=\"M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5\n" +
                "\t\tc-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z\"/>\n" +
                "</svg>",
            callback: function() {
                Livlag.Callback(scope.setPage, [scope.pageNumber + 1], scope);
            },

        }));

    }
    initNumbersButton() {
        this.numberContainer.html('');

        let scope = this;
        let id = this.parent.schemaName;
        let getBtn = function(index) {
            return $('<a/>', {
                id: id + "-pagination-button-" + index,
                class: 'pagination-button',
                text: index,
                click: function() {
                    Livlag.Callback(scope.setPage, [index], scope);
                }
            });
        }

        if (this.PageCount <=7) {
            for (let i = 1; i <= this.PageCount; i++) {
                this.numberContainer.append(getBtn(i));
            }
        }
        else if (this.pageNumber >= 1 && this.pageNumber <= 4) {
            for (let i = 1; i <= 5; i++) {
                this.numberContainer.append(getBtn(i));
            }
            this.numberContainer.append($('<span/>', {
                class: 'pagination-span',
                text: "..."
            }));
            this.numberContainer.append(getBtn(this.PageCount));
        }
        else if (this.pageNumber >= this.PageCount - 4 && this.pageNumber <= this.PageCount) {
            this.numberContainer.append(getBtn(1));
            this.numberContainer.append($('<span/>', {
                class: 'pagination-span',
                text: "..."
            }));
            for (let i = this.PageCount - 4; i <= this.PageCount; i++) {
                this.numberContainer.append(getBtn(i));
            }
        }
        else {
            this.numberContainer.append(getBtn(1));
            this.numberContainer.append($('<span/>', {
                class: 'pagination-span',
                text: "..."
            }));

            this.numberContainer.append(getBtn(this.pageNumber - 1));
            this.numberContainer.append(getBtn(this.pageNumber));
            this.numberContainer.append(getBtn(this.pageNumber + 1));

            this.numberContainer.append($('<span/>', {
                class: 'pagination-span',
                text: "..."
            }));
            this.numberContainer.append(getBtn(this.PageCount));
        }
        this.setActiveButton();
    }
    setActiveButton() {
        let btnId = "#" + this.parent.schemaName + "-pagination-button-" + this.pageNumber;
        console.log(btnId);
        document.querySelectorAll('.pagination-links a').forEach((link) => {
            link.classList.remove("active");
        })
        document.querySelector(btnId).classList.add("active")
    }
    setRowCount(rowCount) {
        if (this.rowCount == rowCount) {
            return;
        }

        let currentRow = this.currentRow;
        let currentPage = currentRow / rowCount;
        if (currentPage % 1 > 0) {
            currentPage = currentPage - currentPage % 1 + 1;
        }
        this.rowCount = rowCount;
        this.setPage(currentPage + 1, false);
    }
    setPage(pageNumber, check = true) {
        if (check) {
            if (this.pageNumber === pageNumber || pageNumber < 1 || pageNumber > this.PageCount) {
                return;
            }
        }
        this.pageNumber = pageNumber;
        this.isReload = false;
        this.parent.loadTable(function() {
            this.isReload = true;
            this.reload();
        }, this);

    }

    reload() {
        this.initPageCount();
        this.initNumbersButton();
    }



    get currentRow() {
        return this.rowCount * (this.pageNumber - 1);
    }
    getConfig() {
        return {
            rowCount: this.rowCount,
            startPosition: this.currentRow
        }
    }
}

export class SortViewModel {
    constructor(config) {
        this.config = config;
    }
    get parent() {
        return this.config.parentObject;
    }

    init() {
        let scope = this;
        let shema = this.parent.schemaName;
        let getBtn = function(col) {
            return $('<a/>', {
                id: shema + col + '-order-button',
                class: 'order-button',
                html: "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\" viewBox=\"0 0 386.257 386.257\">\n" +
                    "    <polygon points=\"0,96.879 193.129,289.379 386.257,96.879 \"  class=\"active-path\"  fill=\"#FFFFFF\"/>\n" +
                    "</svg>\n",
                click: function() {
                    Livlag.Callback(scope.setOrder, [col], scope);
                }
            });
        };
        for(let col in this.parent.columns) {
            $("#" + shema + col + " .table-cell").append(getBtn(col));
            getBtn(col);
            this.addOrderColumnClass(col);
        }
    }

    addOrderColumnClass(column) {
        let currentColumn = this.parent.columns[column];
        if (currentColumn.orderDirection === Livlag.Enums.OrderDirection.ASC) {
            document.querySelector("#" + this.parent.schemaName + column).classList.add("down")
            document.querySelector("#" + this.parent.schemaName + column).classList.remove("up")
        } else if (currentColumn.orderDirection === Livlag.Enums.OrderDirection.DESC) {
            document.querySelector("#" + this.parent.schemaName + column).classList.remove("down")
            document.querySelector("#" + this.parent.schemaName + column).classList.add("up")
        }
    }

    setOrder(column) {
        let currentColumn = this.parent.columns[column];
        let pos = currentColumn.orderPosition;
        for(let col in this.parent.columns) {
            let column = this.parent.columns[col];
            if (column.orderPosition) {
                if (pos) {
                    if (column.orderPosition > pos) {
                        continue;
                    }
                }
                column.orderPosition++;
            }
        }
        currentColumn.orderPosition = 1;
        currentColumn.orderDirection = Livlag.GetOrderDirection(currentColumn.orderDirection);

        this.parent.resetPagination();
        this.parent.loadTable();
    }
}
