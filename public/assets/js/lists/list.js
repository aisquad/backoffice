export class List {
    constructor(id, data) {
        this.id = id;
        this.$container = $(`${id}`);
        this.data = data;
        this.dispatch();
    }
    
    dispatch() {
        this.$container.html('');
        this.renderItems();
    }

    renderItems() {
        this.data.forEach(item => this.renderItem(item));
    }

    renderItem(item) {
        let $element;
        if (item.hasOwnProperty('image_url')) {
            // News update item
            $element = this.createNewsElement(item);
        } else if (item.hasOwnProperty('badge_color')) {
            // Activity item
            $element = this.createActivityElement(item);
        }
        if ($element) {
            this.$container.append($element);
        }
    }

    createNewsElement(item) {
        return $('<div>').addClass('post-item clearfix')
            .append(
                $('<img>').attr('src', `/assets/img/${item.image_url}`).attr('alt', `News Image - ${item.image_url}`),
                $('<h4>').append($('<a>').attr('href', '#').text(item.title)),
                $('<p>').text(this.truncateText(item.content, 100))
            );
    }

    createActivityElement(item) {
        const $activityItem = $('<div>').addClass('activity-item d-flex');
        
        const $timeLabel = $('<div>').addClass("activite-label").text(item.time_label);
        
        const $img = $('<i>').addClass(`bi bi-circle-fill activity-badge text-${item.badge_color} align-self-start`);
        
        const $activityContent = $('<div>').addClass('activity-content').html(this.formatContent(item));
        
        return $activityItem.append($timeLabel, $img, $activityContent);
    }

    formatContent(item) {
        if (item.link_text && item.link_url) {
            return item.content.replace(item.link_text, `<a href="public/assets/img/${item.link_url}" class="fw-bold text-dark">${item.link_text}</a>`);
        }
        return item.content;
    }

    truncateText(text, maxLength) {
        if (text.length <= maxLength) return text;
        return `${text.substr(0, maxLength)}...`;
    }
}
