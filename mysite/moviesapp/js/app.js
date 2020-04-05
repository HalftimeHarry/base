apiURL = "https://8080-d4aaaf7e-1459-47dc-a803-efef7a78c0ad.ws-us02.gitpod.io/mysite/web/api/movies"

var App = Vue.extend({});

var deleteMovie = Vue.extend({
    template: '#delete-movie',
    http:{
        headers:{
            'Accept' : 'json',
            'Content-Type' : 'application/hal+json',
            'Authorization' : 'Basic bXlzaXRlX3VzZXI6TUFEY2FwKDEyMyk='
        }
    },

    methods:{
        deleteTheMovie: function(){
            this.$http.delete('https://8080-d4aaaf7e-1459-47dc-a803-efef7a78c0ad.ws-us02.gitpod.io/mysite/web/node/' + this.$route.params.movieID, function(response){
                this.$route.router.go('/');
            })
        }
    }
});

var createMovie = Vue.extend({
    template: '#create-movie',

    data: function(){
        return {
            title: '',
            body: '',
            success:''
        }
    },

    http:{
        headers:{
            'Accept' : 'json',
            'Content-Type' : 'application/hal+json',
            'Authorization' : 'Basic bXlzaXRlX3VzZXI6TUFEY2FwKDEyMyk='
        }
    },

    ready: function(){
        //this.createTheMovie();
    },

    methods: {
        createTheMovie: function(event){
            event.preventDefault();
            var data = {
                '_links':{
                    'type' : {
                        'href' : 'https://8080-d4aaaf7e-1459-47dc-a803-efef7a78c0ad.ws-us02.gitpod.io/mysite/web/rest/type/node/movies'
                    }
                },
                'title':[
                    {
                        'value' : this.title
                    }
                ],
                'body':[
                    {
                        'value' : this.body
                    }
                ]
            }

            this.$http.post('https://8080-d4aaaf7e-1459-47dc-a803-efef7a78c0ad.ws-us02.gitpod.io/mysite/web/entity/node', data, function(response){
                this.$set('success', 'ok');
                this.$set('title', '');
                this.$set('body', '');
            });
        }
    }
})

var movieList = Vue.extend({
    template: '#movie-list-template',

    data: function() {
        return {
            movies: '',
            liveFilter: '',
            genreFilter: '',
            genres: '',
            movie:''
        }
    },

    ready: function(){
        this.getMovies();
    },

    methods: {
        getMovies: function(){
            this.$set('movie', '');
            this.$http.get(apiURL, function(movies){
                this.$set('movies', movies);

                genresArr=[];

                jQuery.each(movies, function(index, movie){
                    jQuery.each(movie.field_genres, function(index, genre){
                        if(jQuery.inArray(genre.value, genresArr) === -1) {
                            genresArr.push(genre.value);
                        }
                    });
                });

                this.$set('genres', genresArr);
                //console.log(JSON.stringify(genresArr));

            });
        }
    }
})

var singleMovie = Vue.extend({
    template: '#single-movie-template',

    data: function(){
        return {
            movie:''
        }
    },

    ready: function(){
        this.getTheMovie();
    },

    methods: {
        getTheMovie: function(){
            this.$http.get(apiURL + '/' + this.$route.params.movieID, function(movie){
                this.$set('movie', movie);
                console.log(JSON.stringify(movie));
            })
        }
    }
})


var router = new VueRouter();

router.map({
    '/':{
        component: movieList
    },
    'create':{
        component: createMovie
    },
    'movie/:movieID':{
        name: 'movie',
        component: singleMovie
    },
    'delete/:movieID':{
        name: 'delete',
        component: deleteMovie
    }
});

router.start(App, '#app');



/*new Vue({
    el: '#app',

    data: {
        movies: '',
        liveFilter: '',
        genreFilter: '',
        genres: '',
        movie:''
    },

    ready: function(){
        this.getMovies();
    },

    methods: {
        getMovies: function(){
            this.$set('movie', '');
            this.$http.get(apiURL, function(movies){
                this.$set('movies', movies);

                genresArr=[];

                jQuery.each(movies, function(index, movie){
                    jQuery.each(movie.field_genres, function(index, genre){
                        if(jQuery.inArray(genre.value, genresArr) === -1) {
                            genresArr.push(genre.value);
                        }
                    });
                });

                this.$set('genres', genresArr);
                //console.log(JSON.stringify(genresArr));

            });
        },

        getTheMovie: function(movieID){
            this.$http.get(apiURL + '/' + movieID, function(movie){
                this.$set('movie', movie);
                console.log(JSON.stringify(movie));
            })
        }
    }
})*/
