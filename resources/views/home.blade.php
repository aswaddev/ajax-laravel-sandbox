<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AJAX Sandbox</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center">Create Post</h1>
            <div id="errorMessages" style="display:none;"></div>
            <form action="{{ route("posts.store") }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control" type="text" name="title" id="title">
                </div>
                <div class="form-group">
                    <label for="thumbnail">Thumbnail</label>
                    <input class="form-control-file" type="file" name="thumbnail" id="thumbnail">
                </div>
                <div class="form-group">
                    <label for="body">Body</label>
                    <textarea class="form-control" rows="10" name="body" id="body"></textarea>
                </div>
                <button class="btn btn-primary">Submit</button>
            </form>
            <img id="loader" class="m-auto" style="display:none;" src="https://media0.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" alt="Loading...">
            <hr>

            <h1 class="text-center">All Posts</h1>
            <div class="row m-5">
                @foreach ($posts as $post)
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset("/storage/" . $post->thumbnail) }}" class="card-img-top" alt="{{ $post->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ $post->body }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <script>
            console.clear();
            var form = document.forms[0];

            var xhr;

            if(window.XMLHttpRequest){
                xhr = new XMLHttpRequest();
            }else{
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }

            form.addEventListener("submit", function(e) {
                e.preventDefault();
                var formData = new FormData(form);
                // formData.append("author", "Aswad Ali");
                // for (var key of formData.values()) {
                //     console.log(key);
                // }

                // Holds the status of the XMLHttpRequest.
                // 0: request not initialized
                // 1: server connection established
                // 2: request received
                // 3: processing request
                // 4: request finished and response is ready
                // console.log(this);
                xhr.onreadystatechange = function() {
                    console.log("Loading...");
                    form.style.display = "none";
                    loader.style.display = "block";
                    if(this.readyState == 4 && this.status == 201){
                        var data = JSON.parse(this.responseText);
                        console.log(data.message);
                        form.style.display = "block";
                        loader.style.display = "none";
                        window.location.reload();
                    }else if(this.readyState == 4 && this.status == 422){
                        var data = JSON.parse(this.responseText);
                        var errors = data.errors;
                        form.style.display = "block";
                        loader.style.display = "none";
                        errorMessages.innerHTML = "<l>";
                        var keys = Object.keys(errors);
                        keys.forEach(key => {
                            // errors["title"]
                            // errors.title
                            errorMessages.innerHTML += "<li class='alert alert-danger'>" + errors[key][0] + "</li>";
                        });
                        errorMessages.innerHTML += "</ul>";
                        errorMessages.style.display = "block";
                    }
                }

                xhr.open(form.method, form.action);
                xhr.send(formData);
            })
        </script>
    </body>
</html>
