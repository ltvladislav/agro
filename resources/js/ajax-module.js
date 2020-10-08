import {isExist} from "./delta-functions";

window.postConfig = {
    categoryId: 0,
    pageNumber: 1
};

document.querySelectorAll('.category-item').forEach(function(item) {
    item.addEventListener('click', categoryClick, true);
});

function categoryClick(categoryItem) {
    let item = categoryItem.target;
    let categoryId = item.getAttribute('data-category-id');
    let categorySlug = item.getAttribute('data-category-slug');

    //window.history.pushState({}, '', `posts/${categorySlug}`);
    window.postConfig = {
        categoryId: categoryId,
        pageNumber: 0
    };
    Helper.callService(window.site.language + '/postService', {
            methodName: 'getPostByCategory',
            data: window.postConfig
        }, function(response) {
        document.querySelector('.news-wrapper').innerHTML = response && response.view;

        if (!response.existsMore) {
            if (!document.querySelector('.pagination-wrap').classList.contains('hidden')) {
                document.querySelector('.pagination-wrap').classList.add('hidden');
            }
        }
        else if (document.querySelector('.pagination-wrap').classList.contains('hidden')) {
            document.querySelector('.pagination-wrap').classList.remove('hidden');
        }
        window.postConfig.pageNumber++;
    })
}

global.showMoreClick = function(showMoreButton) {
    showMoreButton.preventDefault();

    let type = document.querySelector('[data-paginate]');
    if (!type) {
        return;
    }
    let data = type.getAttribute('data-paginate');

    if (showMores[data]) {
        showMores[data]();
    }
}
let showMores = {
    posts: function() {
        Helper.callService(window.site.language + '/postService', {
            methodName: 'getPostByCategory',
            data: window.postConfig
        }, function(response) {
            document.querySelector('.news-wrapper')
                .insertAdjacentHTML('beforeend', response && response.view);
            if (!response.existsMore) {
                document.querySelector('.pagination-wrap').classList.add('hidden');
            }
            window.postConfig.pageNumber++;
        })
    }

}
