<p>Click on an Overlay, to switch it's zIndex with the highest value in the stack:</p>
<div class="overlay-example" id="overlay-stack">
    <p class="filler">
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc pretium quam eu mi varius pulvinar. Duis orci arcu, ullamcorper sit amet, luctus ut, interdum ac, quam. Pellentesque euismod. Nam tincidunt, purus in ultrices congue, urna neque posuere arcu, aliquam tristique purus sapien id nulla. Etiam rhoncus nulla at leo. Cras scelerisque nisl in nibh. Sed eget odio. Morbi elit elit, porta a, convallis sit amet, rhoncus non, felis. Mauris nulla pede, pretium eleifend, porttitor at, rutrum id, orci. Quisque non urna. Nulla aliquam rhoncus est.
        <select class="needs-shim">
            <option>Prevent IE6 Bleedthrough</option>
        </select>
    </p>
</div>

<script type="text/javascript">
YUI(<?php echo $yuiConfig ?>).use(<?php echo $requiredModules ?>, function(Y) {

    var overlays = [],
        overlay,
        n = 6,
        xy = Y.Node.get("#overlay-stack").getXY();

    function getOverlayXY(xy, i) {
        return [xy[0] + i * 60, xy[1] + i * 40];
    }

    for (var i = 0; i < n; ++i) {

        ovrXY = getOverlayXY(xy, i);
        ovrZIndex = i+1;

        // Setup n Overlays, with increasing zIndex values
        overlay = new Y.Overlay({
            zIndex:ovrZIndex,
            xy:ovrXY,
            width:"8em",
            height:"8em",
            headerContent: 'Overlay <span class="yui-ovr-number">' + i + '</span>',
            bodyContent: "zIndex = " + ovrZIndex
        });

        overlay.render("#overlay-stack");
        overlays.push(overlay);

        // Update body whenever zIndex changes
        overlay.after("zIndexChange", function(e) {
            this.set("bodyContent", "zIndex = " + e.newVal);
        });
    }

    function onStackMouseDown(e) {
        var widget = Y.Widget.getByNode(e.target);

        // If user clicked on an Overlay, bring it to the top of the stack
        if (widget && widget instanceof Y.Overlay) {
            bringToTop(widget);
        }
    }

    // zIndex comparator
    function byZIndexDesc(a, b) {
        if (!a || !b || !a.hasImpl(Y.WidgetStack) || !b.hasImpl(Y.WidgetStack)) {
            return 0;
        } else {
            var aZ = a.get("zIndex");
            var bZ = b.get("zIndex");

            if (aZ > bZ) {
                return -1;
            } else if (aZ < bZ) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    function bringToTop(overlay) {

        // Sort overlays by their numerical zIndex values
        overlays.sort(byZIndexDesc);

        // Get the highest one
        var highest = overlays[0];

        // If the overlay is not the highest one, switch zIndices
        if (highest !== overlay) {
            var highestZ = highest.get("zIndex");
            var overlayZ = overlay.get("zIndex");

            overlay.set("zIndex", highestZ);
            highest.set("zIndex", overlayZ);
        }
    }

    Y.on("mousedown", onStackMouseDown, "#overlay-stack");
});
</script>
