<footer class="footer" id="footer">
    <div class="container text-center">
        <div class="row content">
            <div class="col-md-4 col-md-offset-4">
                <ul class="connect">
                    <li>
                        <a href="{{ url('/') }}">
                            <i class="large ion-ios-home"></i>
                        </a>
                    </li>
                </ul>
                <div class="links">
                    <a href="{{ url('link') }}">
                        {{ lang('Links') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="copy-right text-center">
        <span>{{!! config('blog.footer.meta') !!}}</span>
    </div>
</footer>