                                    @if ($cat->where('parent_id', $cat->id)->count() > 0)
                                    <ul>
                                    @foreach ($cat->where('parent_id', $cat->id)->get() as $cat)
                                      <li id="list-{{$cat->id}}"><div>


                                      <span class="text">
                                      <a href="{{URL::to('/help/cat/'.$cat->id)}}">{{$cat->name}}</a>
                                    @if ($cat->help->count() > 0)
                                    <small>({{$cat->help->count()}})</small>
                                    @endif
                                      </span>

                                      </div>

                                      @include('user.help.categoryTreeList', array('cat', $cat))

                                      </li>
                                    @endforeach
                                    </ul>
                                    @endif