<div class="modal fade" id="modalForFacebookMeta" tabindex="-1" aria-labelledby="modalForFacebookMeta"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered max-w-655px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0 pt-2 px-2 d-flex justify-content-end">
                <button type="button" class="bg-transparent border-0 btn-close fz-22 p-0 text-black"
                        data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i>
                </button>
            </div>
            <div class="modal-body px-4 px-sm-5 pt-0">
                <div class="swiper instruction-carousel pb-3">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="swiper-slide">
                                <div class="">
                                    <div class="d-flex justify-content-center mb-5">
                                        <img height="60"
                                             src="{{ dynamicAsset(path: 'public/assets/back-end/img/svg/facebook.svg') }}"
                                             loading="lazy" alt="">
                                    </div>
                                    <div class="text-start title-color mb-3">
                                        <h4 class="lh-md font-weight-bolder fz-16">
                                            {{ translate('how_to_get_the_meta_pixel_id') }}
                                        </h4>
                                        <p class="opacity--80">
                                            {{ translate('to_get_your_meta_pixel_id,_log_into_your_meta_business_manager_account.') }}
                                            {{ translate('go_to_the_events_manager,_select_your_desired_business_account,_and_find_data_sources.') }}
                                            {{ translate('your_pixel_id_will_be_shown_in_the_detailed_section_of_the_property_you_select.') }}
                                            {{ translate('simply_copy_the_pixel_id_from_there.') }}
                                        </p>
                                    </div>

                                    <div class="text-start title-color mb-3">
                                        <h4 class="lh-md font-weight-bolder fz-16">
                                            {{ translate('where_to_use_the_meta_pixel_id') }}
                                        </h4>
                                        <p class="opacity--80">
                                            {{ translate('find_the_marketing_tools_feature_from_your_admin_panel_and_follow_the_instructions:') }}
                                        </p>
                                        <ol class="d-flex flex-column gap-2 title-color opacity--80">
                                            <li>
                                                {{ translate('navigate_to_the_meta_pixel_id_section_under_the_marketing_tools_feature.') }}
                                            </li>
                                            <li>
                                                {{ translate('turn_on_the_toggle_button.') }}
                                            </li>
                                            <li>
                                                {{ translate('paste_your_meta_pixel_id_into_the_input_box_and_click_submit.') }}
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
