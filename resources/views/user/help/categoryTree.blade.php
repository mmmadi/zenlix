                                    @if ($cat->where('parent_id', $cat->id)->count() > 0)
                                    <ul>
                                    @foreach ($cat->where('parent_id', $cat->id)->get() as $cat)
                                      <li id="list-{{$cat->id}}"><div>


                                        <span class="handle ui-sortable-handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                        </span>

                                      <span class="text">{{$cat->name}}</span>

                                    <span class="pull-right">
                                            <a href='{{URL::to('/help/edit/category/'.$cat->id)}}' style="text-decoration:none; color:#000000 !important;" class="fa fa-edit"></a>
                                            <a href='#' style="text-decoration:none; color:#000000 !important;" class="fa fa-trash-o del_el" data-id="{{$cat->id }}"></a>
                                            
                                            </span>
                                      </div>

                                      @include('user.help.categoryTree', array('cat', $cat))

                                      </li>
                                    @endforeach
                                    </ul>
                                    @endif