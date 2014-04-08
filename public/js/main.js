"use strict";

var tweets = [];
var tweet = null;
var tweet_text = null;
var tweet_time = null;
var bag = [];
var showtime = 15; // Time in seconds between tweet rotation

function getTweetsUrl() {
    var t = window.location.pathname.split('/');
    t.pop();
    return t.join('/') + '/tweets-data';
}

function filterNew(tweet) {
    return (Date.now() - Date.parse(tweet.created_at) > 3600000);
}

function getTweets() {
    jQuery.getJSON(getTweetsUrl(), function(data) {
        data.reverse();
        tweets = data.filter(filterNew);;
    });
}

function scaleDown() {
    tweet.css('transform', 'scale(0)');
    tweet.css('opacity', '0');
}

function scaleUp() {
    tweet.css('transform', 'scale(1)');
    tweet.css('opacity', '1');
}

function convertDate(date) {
    return moment(date).tz('America/Los_Angeles').format('dddd MMM Do h:mm a');
}

function replaceText() {
    var random = getRandomBag();
    tweet_text.html(tweets[random].text);
    tweet_time.html('Posted ' + convertDate(tweets[random].created_at));
}

function has(set, item) {
    for (var i in set) {
        if (set[i] == item) {
            return true;
        }
    }
    return false;
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

/**
 * This function returns a random index for the tweets array that has not
 * already been displayed. Once all tweets have been displayed it resets. This
 * ensures an even distribution of showtime for each tweet in the collection.
 */
function getRandomBag() {
    var unused = [];

    if (tweets.length == bag.length) {
        bag = [];
    }

    for (var i = tweets.length - 1; i >= 0; i--) {
        if (!has(bag, i)) {
            unused.push(i);
        }
    };

    var pick = unused[getRandomInt(0, unused.length - 1)];
    if (pick !== undefined) {
        bag.push(pick);
    }

    return pick;
}

function update() {
    getTweets();
    scaleDown();
    window.setTimeout(replaceText, 1000);
    window.setTimeout(scaleUp, 2000);
}

jQuery(document).ready(function() {
    tweet = jQuery('#tweet');
    tweet_text = jQuery('#tweet-text');
    tweet_time = jQuery('#tweet-time');
    update();

    window.setInterval(update, showtime * 1000);
});