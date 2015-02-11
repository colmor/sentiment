# Twitter Sentiment Analysis

----
## About

A basic demonstration of this project is available [here](http://colmor.me/sentiment/). 

The demo allows a user to input a search term and choose a number of tweets to grab from the Twitter search API. Two classifiers are available, my own Naive-Bayes implementation and the [Alchemy API](http://www.alchemyapi.com). The custom classifier is pretty basic. It is trained off a corpus of pre-classified tweets obtained from [Sentiment140](http://help.sentiment140.com/for-students)-- the classification of these tweets is again pretty basic (postive emoticons imply positive, negative emoticons imply negative), but it certainly beats classifying 1.6m by hand. The training involves building a dictionary of tokens that appear in the corpus and assigning each a probability of being positive/negative based on its frequency in each class. AlchemyAPI, seemingly, uses some form of Alchemy in its process (specifically, deep learning).

Other useful tools that aided in building this included Nick Downie's [Chart.js](http://www.chartjs.org/), Yahoo's [PureCSS](http://purecss.io/) and J7mbo's [PHP wrapper](https://github.com/J7mbo/twitter-api-php) for Twitter API calls. 

----
