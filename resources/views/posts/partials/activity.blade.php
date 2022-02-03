        <div class="container">

            <div class="row"> 
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Most Comented</h5>
                        <h6 class="card-subtitle mb-2 text-muted">What people are curently talking about</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($mostCommented as $post)
                            <li class="list-group-item">
                                <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                    {{ $post->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="row mt-4"> 
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Most Activ </h5>
                        <h6 class="card-subtitle mb-2 text-muted">Users with most blog post written</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($mostActiv as $user)
                            <li class="list-group-item">
                                {{ $user->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row mt-4"> 
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Most Activ Last Month</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Users with most blog post written in the month</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($mostActivLastMonth as $user)
                            <li class="list-group-item">
                                {{ $user->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
        </div>