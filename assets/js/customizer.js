/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Declare vars
	var api = wp.customize;

	api('ciah_contactinfo_text', function( value ) {
		value.bind( function( newval ) {
			$( '.contact-info-content' ).html( newval );
		});
	});
	api('ciah_contactinfo_button_txt', function( value ) {
		value.bind( function( newval ) {
			$( '.contact-info-button a' ).text( newval );
		});
	});
	api( 'ciah_contactinfo_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_top_padding">#contact-info-wrap { padding-top: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_bottom_padding">#contact-info-wrap { padding-bottom: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_tablet_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_tablet_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_tablet_top_padding">@media (max-width: 768px){#contact-info-wrap { padding-top: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_tablet_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_tablet_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_tablet_bottom_padding">@media (max-width: 768px){#contact-info-wrap { padding-bottom: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_mobile_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_mobile_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_mobile_top_padding">@media (max-width: 480px){#contact-info-wrap { padding-top: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_mobile_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_mobile_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_mobile_bottom_padding">@media (max-width: 480px){#contact-info-wrap { padding-bottom: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_bg', function( value ) {
		value.bind( function( to ) {
			$( '#contact-info-wrap' ).css( 'background-color', to );
		} );
	} );
	api( 'ciah_contactinfo_border', function( value ) {
		value.bind( function( to ) {
			$( '#contact-info-wrap' ).css( 'border-color', to );
		} );
	} );
	api( 'ciah_contactinfo_color', function( value ) {
		value.bind( function( to ) {
			$( '#contact-info-wrap' ).css( 'color', to );
		} );
	} );
	api( 'ciah_contactinfo_links_color', function( value ) {
		value.bind( function( to ) {
			$( '.contact-info-content a' ).css( 'color', to );
		} );
	} );
	api( 'ciah_contactinfo_links_color_hover', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_links_color_hover' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_links_color_hover">.contact-info-content a:hover { color: ' + to + '!important; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_button_border_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_button_border_radius' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_button_border_radius">#contact-info .contactinfo-button { border-radius: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_button_bg', function( value ) {
		value.bind( function( to ) {
			$( '#contact-info .contactinfo-button' ).css( 'background-color', to );
		} );
	} );
	api( 'ciah_contactinfo_button_color', function( value ) {
		value.bind( function( to ) {
			$( '#contact-info .contactinfo-button' ).css( 'color', to );
		} );
	} );
	api( 'ciah_contactinfo_button_hover_bg', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_button_hover_bg' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_button_hover_bg">#contact-info .contactinfo-button:hover { background-color: ' + to + '!important; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
	api( 'ciah_contactinfo_button_hover_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-ciah_contactinfo_button_hover_color' );
			if ( to ) {
				var style = '<style class="customizer-ciah_contactinfo_button_hover_color">#contact-info .contactinfo-button:hover { color: ' + to + '!important; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		} );
	} );
    api( 'ciah_contactinfo_text_typo_font_family', function(value) {
        value.bind( function( to ) {
            if ( to ) {
                var idfirst     = ( to.trim().toLowerCase().replace( ' ', '-' ), 'customizer-ciah_contactinfo_text_typo_font_family' );
                var font        = to.replace( ' ', '%20' );
                    font        = font.replace( ',', '%2C' );
                    font        = ciah_contactinfo.googleFontsUrl + '/css?family=' + to + ':' + ciah_contactinfo.googleFontsWeight;

                if ( $( '#' + idfirst ).length ) {
                    $( '#' + idfirst ).attr( 'href', font );
                } else {
                    $( 'head' ).append( '<link id="' + idfirst + '" rel="stylesheet" type="text/css" href="' + font + '">' );
                }
            }
            var $child = $( '.customizer-ciah_contactinfo_text_typo_font_family' );
            if ( to ) {
                var style = '<style class="customizer-ciah_contactinfo_text_typo_font_family">#contact-info .contact-info-content{font-family: ' + to + ';}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });
    api('ciah_contactinfo_text_typo_font_size', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contact-info-content' ).css( 'font-size', newval );
            }
        });
    });
    api('ciah_contactinfo_text_typo_font_weight', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contact-info-content' ).css( 'font-weight', newval );
            }
        });
    });
    api('ciah_contactinfo_text_typo_font_style', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contact-info-content' ).css( 'font-style', newval );
            }
        });
    });
    api('ciah_contactinfo_text_typo_transform', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contact-info-content' ).css( 'text-transform', newval );
            }
        });
    });
    api('ciah_contactinfo_text_typo_line_height', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contact-info-content' ).css( 'line-height', newval );
            }
        });
    });
    api('ciah_contactinfo_text_typo_spacing', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contact-info-content' ).css( 'letter-spacing', newval );
            }
        });
    });
    api( 'ciah_contactinfo_button_typo_font_family', function(value) {
        value.bind( function( to ) {
            if ( to ) {
                var idfirst     = ( to.trim().toLowerCase().replace( ' ', '-' ), 'customizer-ciah_contactinfo_button_typo_font_family' );
                var font        = to.replace( ' ', '%20' );
                    font        = font.replace( ',', '%2C' );
                    font        = ciah_contactinfo.googleFontsUrl + '/css?family=' + to + ':' + ciah_contactinfo.googleFontsWeight;

                if ( $( '#' + idfirst ).length ) {
                    $( '#' + idfirst ).attr( 'href', font );
                } else {
                    $( 'head' ).append( '<link id="' + idfirst + '" rel="stylesheet" type="text/css" href="' + font + '">' );
                }
            }
            var $child = $( '.customizer-ciah_contactinfo_button_typo_font_family' );
            if ( to ) {
                var style = '<style class="customizer-ciah_contactinfo_button_typo_font_family">#contact-info .contactinfo-button{font-family: ' + to + ';}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });
    api('ciah_contactinfo_button_typo_font_size', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contactinfo-button' ).css( 'font-size', newval );
            }
        });
    });
    api('ciah_contactinfo_button_typo_font_weight', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contactinfo-button' ).css( 'font-weight', newval );
            }
        });
    });
    api('ciah_contactinfo_button_typo_font_style', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contactinfo-button' ).css( 'font-style', newval );
            }
        });
    });
    api('ciah_contactinfo_button_typo_transform', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contactinfo-button' ).css( 'text-transform', newval );
            }
        });
    });
    api('ciah_contactinfo_button_typo_line_height', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contactinfo-button' ).css( 'line-height', newval );
            }
        });
    });
    api('ciah_contactinfo_button_typo_spacing', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#contact-info .contactinfo-button' ).css( 'letter-spacing', newval );
            }
        });
    });
} )( jQuery );
