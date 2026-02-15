# Smart Dairy Ltd. - Mobile Accessibility Guide

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | H-018 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | UI/UX Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | QA Lead |
| **Status** | Draft |
| **Classification** | Implementation Document |

---

## Document Control

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | UI/UX Lead | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Accessibility Standards](#2-accessibility-standards)
3. [Semantic Labels](#3-semantic-labels)
4. [Screen Reader Support](#4-screen-reader-support)
5. [Navigation](#5-navigation)
6. [Touch Targets](#6-touch-targets)
7. [Color & Contrast](#7-color--contrast)
8. [Typography](#8-typography)
9. [Images & Media](#9-images--media)
10. [Forms & Inputs](#10-forms--inputs)
11. [Animations](#11-animations)
12. [Audio & Haptics](#12-audio--haptics)
13. [Testing Accessibility](#13-testing-accessibility)
14. [Accessibility Scanner](#14-accessibility-scanner)
15. [Voice Control](#15-voice-control)
16. [Switch Access](#16-switch-access)
17. [Documentation](#17-documentation)
18. [Appendices](#18-appendices)

---

## 1. Introduction

### 1.1 Purpose

This guide establishes accessibility standards and best practices for the Smart Dairy mobile application, ensuring that all users, including farm workers with disabilities, can effectively use the system.

### 1.2 Accessibility Importance

Accessibility is not just a legal requirement but a fundamental aspect of inclusive design. For Smart Dairy's farm worker user base, accessibility features are critical because:

- **Diverse Workforce**: Farm workers may have varying abilities including vision impairments, motor disabilities, or cognitive differences
- **Challenging Environment**: Outdoor farm environments with bright sunlight, dust, and weather conditions require special accessibility considerations
- **Operational Efficiency**: Accessible interfaces reduce errors and improve task completion rates for all users
- **Legal Compliance**: Meeting accessibility standards ensures compliance with disability rights legislation

### 1.3 WCAG 2.1 Compliance

The Smart Dairy mobile application shall comply with **WCAG 2.1 Level AA** standards as a minimum requirement. This includes:

- **Perceivable**: Information must be presentable in ways users can perceive
- **Operable**: Interface components must be operable by all users
- **Understandable**: Information and UI operation must be understandable
- **Robust**: Content must work with current and future assistive technologies

### 1.4 Scope

This guide applies to:
- Android application (minimum API 26)
- iOS application (minimum iOS 14)
- All user-facing features and functionality
- Third-party integrations and web views

---

## 2. Accessibility Standards

### 2.1 WCAG 2.1 Level AA Requirements

| Principle | Requirement | Implementation |
|-----------|-------------|----------------|
| Perceivable | 1.1 Text Alternatives | All non-text content has text alternatives |
| Perceivable | 1.3 Adaptable | Content can be presented in different ways |
| Perceivable | 1.4 Distinguishable | Users can see and hear content |
| Operable | 2.1 Keyboard Accessible | All functionality available via keyboard |
| Operable | 2.2 Enough Time | Users have enough time to read/use content |
| Operable | 2.3 Seizures | Content does not cause seizures |
| Operable | 2.4 Navigable | Users can navigate and find content |
| Operable | 2.5 Input Modalities | Input beyond keyboard supported |
| Understandable | 3.1 Readable | Text is readable and understandable |
| Understandable | 3.2 Predictable | UI appears and operates predictably |
| Understandable | 3.3 Input Assistance | Users can avoid and correct mistakes |
| Robust | 4.1 Compatible | Works with assistive technologies |

### 2.2 Platform-Specific Guidelines

#### Android Accessibility Guidelines
- Follow [Android Accessibility Guidelines](https://developer.android.com/guide/topics/ui/accessibility)
- Implement `AccessibilityNodeInfo` properly
- Support `AccessibilityService` interactions
- Use Android Accessibility Scanner for testing

#### iOS Accessibility Guidelines
- Follow [Apple Accessibility Guidelines](https://developer.apple.com/accessibility/)
- Implement `UIAccessibility` protocols
- Support VoiceOver navigation
- Use Accessibility Inspector for testing

### 2.3 Farm Worker Accessibility Standards

Beyond standard WCAG compliance, Smart Dairy implements farm worker-specific accessibility enhancements:

| Consideration | Standard | Rationale |
|--------------|----------|-----------|
| Touch Target Size | 56x56dp minimum | Glove-friendly interaction |
| Contrast Ratio | 7:1 minimum | Outdoor visibility in sunlight |
| Voice Input | Primary input method option | Hands-free operation |
| Audio Feedback | Required for confirmations | Audible confirmation in noisy environments |
| Layout Complexity | Single-column, consistent | Reduced cognitive load |

---

## 3. Semantic Labels

### 3.1 Widget Descriptions

All interactive elements must have meaningful semantic labels that describe their purpose.

#### Android Implementation

```kotlin
// Set content description for screen readers
val submitButton = findViewById<Button>(R.id.submit_button)
submitButton.contentDescription = "Submit milk production report"

// For images, provide descriptive text
val cowIcon = findViewById<ImageView>(R.id.cow_icon)
cowIcon.contentDescription = "Holstein cow icon"

// For decorative images, mark as not important
val decorativeDivider = findViewById<View>(R.id.divider)
decorativeDivider.importantForAccessibility = View.IMPORTANT_FOR_ACCESSIBILITY_NO
```

#### iOS Implementation

```swift
// Set accessibility label for VoiceOver
submitButton.accessibilityLabel = "Submit milk production report"

// For images, provide descriptive text
cowIcon.accessibilityLabel = "Holstein cow icon"

// For decorative images, hide from VoiceOver
decorativeDivider.isAccessibilityElement = false
```

### 3.2 Action Hints

Provide hints that describe what action an element performs.

#### Android

```kotlin
// Add action hint
scanButton.contentDescription = "Scan cow ear tag"
scanButton.accessibilityDelegate = object : View.AccessibilityDelegate() {
    override fun onInitializeAccessibilityNodeInfo(
        host: View, 
        info: AccessibilityNodeInfo
    ) {
        super.onInitializeAccessibilityNodeInfo(host, info)
        info.hintText = "Double-tap to open camera scanner"
    }
}
```

#### iOS

```swift
// Add action hint
scanButton.accessibilityHint = "Opens camera to scan cow ear tag"
```

### 3.3 Dynamic Content Updates

When content changes dynamically, announce changes to screen readers.

#### Android

```kotlin
// Announce status changes
val announcement = "Milk collection complete. Total: 450 liters"
view.announceForAccessibility(announcement)

// For LiveData updates
viewModel.status.observe(this) { status ->
    statusTextView.text = status
    statusTextView.announceForAccessibility(status)
}
```

#### iOS

```swift
// Post accessibility notification
let announcement = "Milk collection complete. Total: 450 liters"
UIAccessibility.post(notification: .announcement, argument: announcement)

// Update accessibility label and notify
statusLabel.accessibilityLabel = status
UIAccessibility.post(notification: .layoutChanged, argument: statusLabel)
```

### 3.4 Semantic Label Examples

| Element | Poor Label | Good Label | Hint |
|---------|-----------|------------|------|
| Button | "Button" | "Record milk yield" | "Double-tap to save entry" |
| Image | "Image" | "Cow health status: Good" | "Swipe up for details" |
| Text Field | "Text field" | "Enter cow identification number" | "Double-tap to edit" |
| Toggle | "Switch" | "Enable automatic alerts" | "Double-tap to toggle" |
| Navigation | "Item 1" | "Dashboard" | "Double-tap to navigate" |

---

## 4. Screen Reader Support

### 4.1 TalkBack (Android)

TalkBack is Android's built-in screen reader. The Smart Dairy app must be fully compatible with TalkBack.

#### TalkBack Gestures

| Gesture | Action |
|---------|--------|
| Swipe right | Move to next element |
| Swipe left | Move to previous element |
| Double-tap | Activate element |
| Swipe up then right | Global context menu |
| Swipe down then right | Local context menu |
| Two-finger swipe up | Read from top |
| Two-finger swipe down | Read continuously |

#### TalkBack Implementation Requirements

```kotlin
// Ensure proper focus order
container.importantForAccessibility = View.IMPORTANT_FOR_ACCESSIBILITY_YES

// Group related content for efficient navigation
ViewCompat.setAccessibilityHeading(headerText, true)

// Provide traversal order
ViewCompat.setAccessibilityTraversalBefore(element1, element2)
```

### 4.2 VoiceOver (iOS)

VoiceOver is iOS's screen reader. The Smart Dairy app must provide full VoiceOver support.

#### VoiceOver Gestures

| Gesture | Action |
|---------|--------|
| Swipe right | Move to next element |
| Swipe left | Move to previous element |
| Double-tap | Activate element |
| Two-finger swipe up | Read all from top |
| Two-finger swipe down | Read all from current position |
| Three-finger swipe | Scroll |
| Two-finger scrub | Go back/close |

#### VoiceOver Implementation Requirements

```swift
// Ensure element is accessible
element.isAccessibilityElement = true

// Set accessibility traits
element.accessibilityTraits = .button

// Group related elements
container.shouldGroupAccessibilityChildren = true

// Define accessibility container order
container.accessibilityElements = [element1, element2, element3]
```

### 4.3 Screen Reader Testing Steps

#### Android TalkBack Testing

1. **Enable TalkBack**:
   - Settings → Accessibility → TalkBack → Turn On
   - Or use volume key shortcut (hold both volume keys)

2. **Basic Navigation Test**:
   - Navigate through all screens using swipe gestures
   - Verify all interactive elements are reachable
   - Confirm focus moves logically through UI

3. **Element Activation Test**:
   - Activate each button and control
   - Verify actions are performed correctly
   - Check that feedback is announced

4. **Form Input Test**:
   - Navigate to text fields
   - Verify keyboard type is appropriate
   - Confirm input is announced correctly

5. **Dynamic Content Test**:
   - Trigger status updates
   - Verify announcements are made
   - Check loading states are communicated

#### iOS VoiceOver Testing

1. **Enable VoiceOver**:
   - Settings → Accessibility → VoiceOver → Turn On
   - Or use Siri: "Turn on VoiceOver"

2. **Basic Navigation Test**:
   - Navigate through all screens using swipe gestures
   - Verify Rotor navigation for headings, links, etc.
   - Confirm focus moves logically

3. **Element Activation Test**:
   - Activate each control with double-tap
   - Verify Magic Tap actions where implemented
   - Check that actions are announced

4. **Screen Change Test**:
   - Navigate between screens
   - Verify screen changes are announced
   - Check that focus moves to appropriate element

---

## 5. Navigation

### 5.1 Focus Management

Focus must be managed logically to ensure users can navigate efficiently.

#### Android Focus Management

```kotlin
// Set focus to new content after navigation
fun navigateToDetails() {
    // Load new content
    loadDetails()
    
    // Move focus to title
    detailsTitle.sendAccessibilityEvent(
        AccessibilityEvent.TYPE_VIEW_FOCUSED
    )
    
    // Announce navigation
    detailsTitle.announceForAccessibility("Showing cow details")
}

// Handle back navigation focus
override fun onBackPressed() {
    super.onBackPressed()
    
    // Return focus to triggering element
    previousButton.requestFocus()
    previousButton.sendAccessibilityEvent(
        AccessibilityEvent.TYPE_VIEW_FOCUSED
    )
}
```

#### iOS Focus Management

```swift
// Set focus after navigation
func navigateToDetails() {
    // Load new content
    loadDetails()
    
    // Move VoiceOver focus
    UIAccessibility.post(
        notification: .screenChanged,
        argument: detailsTitle
    )
}

// Handle modal dismissal
func dismissModal() {
    dismiss(animated: true) {
        // Return focus to trigger button
        UIAccessibility.post(
            notification: .layoutChanged,
            argument: self.triggerButton
        )
    }
}
```

### 5.2 Logical Navigation Order

Navigation must follow a logical order that matches visual layout.

#### Guidelines

1. **Left-to-Right, Top-to-Bottom**: Default reading order
2. **Group Related Content**: Keep related controls together
3. **Skip Navigation**: Provide skip links for repetitive content
4. **Bypass Blocks**: Allow users to skip over navigation menus

#### Android Implementation

```kotlin
// Define traversal order
header.accessibilityTraversalBefore = content
content.accessibilityTraversalBefore = footer

// Mark decorative elements
banner.importantForAccessibility = View.IMPORTANT_FOR_ACCESSIBILITY_NO

// Create accessibility headings
ViewCompat.setAccessibilityHeading(sectionTitle, true)
```

#### iOS Implementation

```swift
// Set accessibility container order
container.accessibilityElements = [
    header,
    content,
    actionButton,
    footer
]

// Mark element as header
sectionTitle.accessibilityTraits.insert(.header)

// Hide decorative element
decorativeView.isAccessibilityElement = false
```

### 5.3 Navigation Patterns

| Pattern | Implementation | Use Case |
|---------|---------------|----------|
| Linear Navigation | Sequential focus | Forms, reading content |
| Hierarchical | Drill-down navigation | Settings, lists |
| Tab Navigation | Bottom navigation | Main app sections |
| Modal Navigation | Focus trap | Dialogs, alerts |
| Skip Links | "Skip to content" | Bypass repetitive content |

---

## 6. Touch Targets

### 6.1 Minimum Touch Target Size

All interactive elements must meet minimum touch target sizes for accessibility.

#### Standard Requirements

| Standard | Minimum Size | Smart Dairy Target |
|----------|-------------|-------------------|
| WCAG 2.1 AA | 44x44dp | 48x48dp |
| Material Design | 48x48dp | 48x48dp |
| iOS HIG | 44x44pt | 48x48pt |
| Farm Worker | 48x48dp | 56x56dp |

#### Android Implementation

```kotlin
// Ensure minimum touch target size
val button = MaterialButton(context).apply {
    minHeight = 56.dpToPx()
    minWidth = 56.dpToPx()
    
    // Add touch delegate for small icons
    post {
        val parent = parent as View
        val touchDelegate = TouchDelegate(
            Rect(
                left - 8.dpToPx(),
                top - 8.dpToPx(),
                right + 8.dpToPx(),
                bottom + 8.dpToPx()
            ),
            this
        )
        parent.touchDelegate = touchDelegate
    }
}

// Extension function for dp conversion
fun Int.dpToPx(): Int = 
    (this * Resources.getSystem().displayMetrics.density).toInt()
```

#### iOS Implementation

```swift
// Set minimum touch target size
button.constraints.forEach { $0.isActive = false }
NSLayoutConstraint.activate([
    button.heightAnchor.constraint(greaterThanOrEqualToConstant: 48),
    button.widthAnchor.constraint(greaterThanOrEqualToConstant: 48)
])

// Increase hit area for small controls
class AccessibleButton: UIButton {
    override func point(inside point: CGPoint, with event: UIEvent?) -> Bool {
        let hitArea = bounds.insetBy(dx: -8, dy: -8)
        return hitArea.contains(point)
    }
}
```

### 6.2 Spacing Between Targets

Adequate spacing prevents accidental activation of adjacent targets.

| Target Size | Minimum Spacing |
|-------------|----------------|
| 48x48dp | 8dp minimum |
| 56x56dp (Farm Worker) | 12dp minimum |
| Small icons (24dp) | 24dp touch target |

### 6.3 Glove-Friendly Considerations

For farm workers wearing gloves:

- **Minimum Target**: 56x56dp
- **Preferred Target**: 72x72dp for primary actions
- **Spacing**: 16dp between targets
- **Visual Feedback**: Clear pressed states

---

## 7. Color & Contrast

### 7.1 WCAG Contrast Ratios

Color contrast must meet WCAG 2.1 AA standards.

| Element | WCAG AA | Smart Dairy Target |
|---------|---------|-------------------|
| Normal text (<18pt) | 4.5:1 | 7:1 |
| Large text (≥18pt bold/24pt) | 3:1 | 4.5:1 |
| UI Components | 3:1 | 4.5:1 |
| Graphics | 3:1 | 4.5:1 |

### 7.2 Smart Dairy Color Palette

#### Primary Colors (Accessible)

| Color | Hex | Usage | Contrast on White |
|-------|-----|-------|-------------------|
| Dairy Blue | #005A9C | Primary actions | 7.2:1 |
| Farm Green | #2D5A27 | Success states | 7.0:1 |
| Alert Orange | #C45C00 | Warnings | 4.6:1 |
| Error Red | #B00020 | Errors | 8.0:1 |
| Dark Text | #1C1B1F | Primary text | 16.0:1 |

#### High Contrast Mode Colors

| Color | Hex | Usage | Contrast on White |
|-------|-----|-------|-------------------|
| HC Blue | #003D6B | Primary actions | 11.0:1 |
| HC Green | #1A3D17 | Success | 10.5:1 |
| HC Text | #000000 | Primary text | 21.0:1 |
| HC Background | #FFFFFF | Background | - |

### 7.3 Color Contrast Guidelines

#### Never Rely on Color Alone

```kotlin
// BAD: Only color indicates status
statusView.setBackgroundColor(Color.RED) // Error indicator only

// GOOD: Color + icon + text
statusView.apply {
    setBackgroundColor(context.getColor(R.color.error))
    setImageResource(R.drawable.ic_error)
    contentDescription = "Error: Cow ID not found"
}
```

```swift
// BAD: Only color indicates status
statusView.backgroundColor = .systemRed

// GOOD: Color + icon + text
statusView.backgroundColor = .systemRed
statusView.image = UIImage(systemName: "exclamationmark.triangle")
statusView.accessibilityLabel = "Error: Cow ID not found"
```

#### Outdoor Visibility Considerations

For farm workers using devices outdoors:

- Use **darker colors** for better sunlight visibility
- Increase contrast ratios to **7:1 minimum**
- Provide **high contrast mode** toggle
- Use **larger text sizes** (minimum 16sp/pt)

### 7.4 Contrast Testing

#### Online Tools
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [Stark Plugin](https://www.getstark.co/) (Figma, Sketch)
- Android Studio Layout Inspector
- Xcode Accessibility Inspector

#### Automated Testing

```kotlin
// Android - Accessibility Scanner
// Run Accessibility Scanner to check contrast

// iOS - Accessibility Inspector
// Use Color Contrast Calculator
```

---

## 8. Typography

### 8.1 Scalable Fonts

Text must scale properly when users change system font sizes.

#### Android Implementation

```kotlin
// Use sp units for text sizes
<TextView
    android:layout_width="wrap_content"
    android:layout_height="wrap_content"
    android:textSize="16sp"
    android:text="Milk Production" />

// Support dynamic text sizing
val textView = findViewById<TextView>(R.id.text_view)
TextViewCompat.setAutoSizeTextTypeWithDefaults(
    textView,
    TextViewCompat.AUTO_SIZE_TEXT_TYPE_UNIFORM
)
```

#### iOS Implementation

```swift
// Use Dynamic Type
label.font = UIFont.preferredFont(forTextStyle: .body)
label.adjustsFontForContentSizeCategory = true

// Support custom fonts with scaling
let metrics = UIFontMetrics(forTextStyle: .body)
label.font = metrics.scaledFont(for: customFont)
```

### 8.2 Readable Font Sizes

| Element | Minimum Size | Recommended |
|---------|--------------|-------------|
| Body Text | 14sp/pt | 16sp/pt |
| Secondary Text | 12sp/pt | 14sp/pt |
| Headings | 18sp/pt | 20sp/pt |
| Buttons | 14sp/pt | 16sp/pt |
| Labels | 12sp/pt | 14sp/pt |
| Farm Worker Mode | 16sp/pt | 18sp/pt |

### 8.3 Line Height and Spacing

| Element | Line Height | Letter Spacing |
|---------|-------------|----------------|
| Body Text | 1.5x font size | 0.5px |
| Headings | 1.2x font size | 0px |
| Buttons | 1.0x font size | 1.25px |
| Captions | 1.4x font size | 0.25px |

---

## 9. Images & Media

### 9.1 Alternative Text (Alt Text)

All images must have appropriate alternative text.

#### Alt Text Guidelines

| Image Type | Alt Text Approach |
|------------|-------------------|
| Informative | Describe content and purpose |
| Decorative | Mark as decorative (no alt) |
| Functional | Describe action performed |
| Complex | Provide detailed description or link |
| Charts/Graphs | Summarize data and trends |

#### Android Implementation

```kotlin
// Informative image
val cowImage = findViewById<ImageView>(R.id.cow_image)
cowImage.contentDescription = "Holstein cow in milking stall"

// Decorative image
decorativeLine.importantForAccessibility = 
    View.IMPORTANT_FOR_ACCESSIBILITY_NO

// Functional image (button)
val scanButton = findViewById<ImageButton>(R.id.scan_button)
scanButton.contentDescription = "Scan cow ear tag"

// Complex chart
productionChart.contentDescription = "Milk production chart showing " +
    "gradual increase from January (450L) to June (680L). " +
    "Double-tap for detailed data table."
```

#### iOS Implementation

```swift
// Informative image
cowImage.accessibilityLabel = "Holstein cow in milking stall"

// Decorative image
decorativeLine.isAccessibilityElement = false

// Functional image
scanButton.accessibilityLabel = "Scan cow ear tag"

// Complex chart
productionChart.accessibilityLabel = "Milk production trend"
productionChart.accessibilityHint = "Swipe up for detailed data"
```

### 9.2 Captions and Transcripts

#### Video Content

All video content must have:
- **Captions** for deaf/hard-of-hearing users
- **Audio descriptions** for blind users (where visual content is important)
- **Transcripts** as alternative format

#### Audio Content

All audio content must have:
- **Text transcripts**
- **Visual indicators** for playback status

### 9.3 Media Player Accessibility

```kotlin
// Custom media player accessibility
mediaPlayer.apply {
    // Labels for controls
    playButton.contentDescription = "Play"
    pauseButton.contentDescription = "Pause"
    
    // Announce state changes
    setOnPreparedListener {
        announceForAccessibility("Video ready to play")
    }
    
    // Progress announcements
    seekBar.apply {
        contentDescription = "Video progress"
        setAccessibilityDelegate(object : View.AccessibilityDelegate() {
            override fun onInitializeAccessibilityNodeInfo(
                host: View,
                info: AccessibilityNodeInfo
            ) {
                super.onInitializeAccessibilityNodeInfo(host, info)
                info.text = "${progress}% complete"
            }
        })
    }
}
```

---

## 10. Forms & Inputs

### 10.1 Labels

All form inputs must have associated labels.

#### Android Implementation

```kotlin
// Use labelFor for explicit association
val label = findViewById<TextView>(R.id.cow_id_label)
val input = findViewById<EditText>(R.id.cow_id_input)
label.labelFor = R.id.cow_id_input

// Programmatic approach
val textInputLayout = findViewById<TextInputLayout>(R.id.cow_id_layout)
textInputLayout.hint = "Cow ID Number"
textInputLayout.contentDescription = "Enter cow identification number"

// Announce field requirements
cowIdInput.accessibilityDelegate = object : View.AccessibilityDelegate() {
    override fun onInitializeAccessibilityNodeInfo(
        host: View,
        info: AccessibilityNodeInfo
    ) {
        super.onInitializeAccessibilityNodeInfo(host, info)
        info.hintText = "Required field. Format: 5 digits"
    }
}
```

#### iOS Implementation

```swift
// Associate label with input
label.accessibilityTraits.insert(.staticText)
textField.accessibilityLabel = "Cow ID Number"
textField.accessibilityHint = "Enter 5 digit cow identification number"

// Group label and input
let container = UIView()
container.isAccessibilityElement = true
container.accessibilityLabel = "\(label.text ?? ""), text field"
container.accessibilityTraits = textField.accessibilityTraits
```

### 10.2 Error Announcements

Errors must be clearly communicated to all users.

#### Android Error Handling

```kotlin
// Announce errors
fun showError(message: String) {
    errorTextView.text = message
    errorTextView.visibility = View.VISIBLE
    errorTextView.announceForAccessibility("Error: $message")
    
    // Set focus to error
    errorTextView.sendAccessibilityEvent(
        AccessibilityEvent.TYPE_VIEW_FOCUSED
    )
}

// Input validation with announcement
cowIdInput.addTextChangedListener(object : TextWatcher {
    override fun afterTextChanged(s: Editable?) {
        if (s?.length != 5) {
            showError("Cow ID must be 5 digits")
        }
    }
    // ... other methods
})
```

#### iOS Error Handling

```swift
// Announce errors
func showError(_ message: String) {
    errorLabel.text = message
    errorLabel.isHidden = false
    
    UIAccessibility.post(
        notification: .announcement,
        argument: "Error: \(message)"
    )
    
    // Move focus to error
    UIAccessibility.post(
        notification: .layoutChanged,
        argument: errorLabel
    )
}
```

### 10.3 Input Types and Keyboards

Use appropriate keyboard types for each input.

| Input Type | Android | iOS |
|------------|---------|-----|
| Cow ID (numeric) | `number` | `.numberPad` |
| Weight | `numberDecimal` | `.decimalPad` |
| Notes | `textMultiLine` | `UIKeyboardType.default` |
| Email | `textEmailAddress` | `.emailAddress` |
| Phone | `phone` | `.phonePad` |
| Date | `date` | `.datePicker` |

### 10.4 Voice Input Support

Enable voice input for hands-free data entry.

```kotlin
// Enable voice input
val editText = findViewById<EditText>(R.id.notes_input)
editText.inputType = InputType.TYPE_TEXT_FLAG_MULTI_LINE or
        InputType.TYPE_TEXT_FLAG_CAP_SENTENCES

// Support speech-to-text
if (SpeechRecognizer.isRecognitionAvailable(context)) {
    voiceButton.visibility = View.VISIBLE
    voiceButton.setOnClickListener {
        startVoiceRecognition()
    }
}
```

```swift
// Enable dictation
textField.returnKeyType = .done

// Custom voice input button
voiceButton.addTarget(self, action: #selector(startVoiceInput), for: .touchUpInside)
```

---

## 11. Animations

### 11.1 Reduced Motion Support

Respect user's preference for reduced motion.

#### Android Implementation

```kotlin
// Check for reduced motion preference
val animatorDurationScale = Settings.Global.getFloat(
    contentResolver,
    Settings.Global.ANIMATOR_DURATION_SCALE,
    1f
)

val reduceMotion = animatorDurationScale == 0f

// Disable or reduce animations
if (reduceMotion) {
    // Skip animation, show final state immediately
    showFinalState()
} else {
    // Play normal animation
    playAnimation()
}

// Or use system animation scale
ObjectAnimator.ofFloat(view, View.ALPHA, 0f, 1f).apply {
    duration = (300 * animatorDurationScale).toLong()
    start()
}
```

#### iOS Implementation

```swift
// Check for reduced motion
let reduceMotion = UIAccessibility.isReduceMotionEnabled

// Adjust animations
if reduceMotion {
    // Disable or simplify animations
    view.alpha = 1.0
} else {
    // Play normal animation
    UIView.animate(withDuration: 0.3) {
        self.view.alpha = 1.0
    }
}

// Use UIView.animate with accessibility considerations
UIView.animate(
    withDuration: 0.3,
    delay: 0,
    options: .allowUserInteraction,
    animations: { /* animation */ },
    completion: nil
)
```

### 11.2 Animation Guidelines

| Animation Type | Accessible Alternative |
|---------------|----------------------|
| Parallax | Static layout |
| Auto-playing | User-controlled or disabled |
| Flashing | None (avoid completely) |
| Transitions | Instant or fade |
| Loading spinners | Static progress indicator |

### 11.3 Seizure Prevention

Avoid content that could trigger seizures:
- No flashing more than 3 times per second
- No red flashing
- Maximum flash area: 25% of screen

---

## 12. Audio & Haptics

### 12.1 Alternative Feedback

Provide non-audio alternatives for all audio feedback.

#### Visual Feedback

```kotlin
// Visual indication of audio event
fun playConfirmationSound() {
    // Play sound
    mediaPlayer.start()
    
    // Visual feedback
    confirmationIcon.visibility = View.VISIBLE
    confirmationIcon.announceForAccessibility("Task completed successfully")
}
```

```swift
func playConfirmationSound() {
    // Play sound
    audioPlayer?.play()
    
    // Visual feedback
    confirmationIcon.isHidden = false
    UIAccessibility.post(notification: .announcement, argument: "Task completed")
}
```

### 12.2 Haptic Feedback

Use haptic feedback as an alternative to audio.

#### Android Haptics

```kotlin
// Use haptic feedback
val vibrator = getSystemService(Context.VIBRATOR_SERVICE) as Vibrator

fun successHaptic() {
    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
        vibrator.vibrate(
            VibrationEffect.createPredefined(VibrationEffect.EFFECT_CLICK)
        )
    }
}

// View haptic feedback
button.performHapticFeedback(HapticFeedbackConstants.CONFIRM)
```

#### iOS Haptics

```swift
// Success feedback
let notificationGenerator = UINotificationFeedbackGenerator()
notificationGenerator.notificationOccurred(.success)

// Impact feedback
let impactGenerator = UIImpactFeedbackGenerator(style: .medium)
impactGenerator.impactOccurred()

// Selection feedback
let selectionGenerator = UISelectionFeedbackGenerator()
selectionGenerator.selectionChanged()
```

### 12.3 Farm Worker Audio Considerations

For noisy farm environments:

- **Volume boost**: Increase default volume by 20%
- **Audio ducking**: Lower background audio when app speaks
- **Visual alternatives**: All audio must have visual equivalent
- **Vibration patterns**: Distinct patterns for different notifications
- **Headset support**: Bluetooth headset compatibility

---

## 13. Testing Accessibility

### 13.1 Automated Testing

#### Android Automated Tests

```kotlin
// Accessibility checks with Espresso
@RunWith(AndroidJUnit4::class)
class AccessibilityTest {
    
    @get:Rule
    val activityRule = ActivityTestRule(MainActivity::class.java)
    
    @Test
    fun testAccessibility() {
        // Run accessibility checks
        AccessibilityChecks.enable().setRunChecksFromRootView(true)
        
        // Test specific screen
        onView(withId(R.id.dashboard))
            .check(matches(isDisplayed()))
    }
}

// Custom accessibility matcher
fun withContentDescription(expected: String): Matcher<View> {
    return object : TypeSafeMatcher<View>() {
        override fun matchesSafely(item: View): Boolean {
            return item.contentDescription == expected
        }
        
        override fun describeTo(description: Description) {
            description.appendText("with content description: $expected")
        }
    }
}
```

#### iOS Automated Tests

```swift
// XCTest accessibility validation
class AccessibilityTests: XCTestCase {
    
    func testAccessibilityLabels() {
        let app = XCUIApplication()
        app.launch()
        
        // Check all buttons have labels
        let buttons = app.buttons.allElementsBoundByIndex
        for button in buttons {
            XCTAssertNotNil(button.label)
            XCTAssertFalse(button.label.isEmpty)
        }
    }
    
    func testScreenReaderSupport() {
        let app = XCUIApplication()
        app.launch()
        
        // Verify accessibility elements exist
        XCTAssertTrue(app.buttons["Submit"].exists)
        XCTAssertTrue(app.textFields["Cow ID"].exists)
    }
}
```

### 13.2 Manual Testing Checklist

See Appendix A for complete checklist.

### 13.3 User Testing

Conduct accessibility user testing with:
- Screen reader users
- Users with motor impairments
- Users with low vision
- Farm workers in actual working conditions

---

## 14. Accessibility Scanner

### 14.1 Android Accessibility Scanner

The Accessibility Scanner is a tool that suggests accessibility improvements.

#### Setup

1. Download "Accessibility Scanner" from Play Store
2. Enable in Settings → Accessibility → Accessibility Scanner
3. Grant necessary permissions

#### Usage

1. Open the app to test
2. Tap the floating Accessibility Scanner button
3. Review suggestions
4. Export report for sharing

#### Interpreting Results

| Issue Type | Priority | Action |
|------------|----------|--------|
| Touch target size | High | Increase to 48x48dp minimum |
| Low contrast | High | Adjust colors to meet 4.5:1 ratio |
| Missing label | High | Add contentDescription |
| Duplicate label | Medium | Make labels unique |
| Editable item | Medium | Add appropriate input type |

### 14.2 Automated Scanning

```kotlin
// Integrate Accessibility Scanner in CI
android {
    testOptions {
        animationsDisabled = true
        
        unitTests.all {
            jvmArgs '-noverify'
        }
    }
}

// Accessibility test dependencies
dependencies {
    androidTestImplementation 'androidx.test.espresso:espresso-accessibility:3.4.0'
    androidTestImplementation 'com.google.android.apps.common.testing.accessibility.framework:accessibility-test-framework:4.0.0'
}
```

---

## 15. Voice Control

### 15.1 Android Voice Access

Support Android Voice Access for hands-free operation.

#### Implementation

```kotlin
// Ensure all interactive elements have visible labels
button.apply {
    text = "Submit Report"  // Used as voice command
    contentDescription = "Submit Report button"
}

// Support numbered labels for Voice Access
fun enableNumberedLabels() {
    // Voice Access automatically numbers visible elements
    // Ensure elements are visible and properly labeled
}
```

#### Voice Access Commands

| Command | Action |
|---------|--------|
| "Tap [label]" | Activate element |
| "Show numbers" | Display numbered overlays |
| "Tap [number]" | Activate numbered element |
| "Scroll down" | Scroll screen |
| "Go back" | Navigate back |
| "Go home" | Return to home |

### 15.2 iOS Voice Control

Support iOS Voice Control for hands-free operation.

#### Implementation

```swift
// Ensure accessibility labels are set
button.accessibilityLabel = "Submit Report"

// Support custom actions
button.accessibilityCustomActions = [
    UIAccessibilityCustomAction(
        name: "Save Draft",
        target: self,
        selector: #selector(saveDraft)
    )
]
```

#### Voice Control Commands

| Command | Action |
|---------|--------|
| "Tap [label]" | Activate element |
| "Show numbers" | Display numbered grid |
| "Tap [number]" | Activate at coordinates |
| "Swipe up/down" | Scroll |
| "Go back" | Navigate back |
| "Show names" | Display element names |

---

## 16. Switch Access

### 16.1 Android Switch Access

Support external switch devices for users with motor disabilities.

#### Implementation

```kotlin
// Ensure sequential navigation
container.isFocusable = true
container.isFocusableInTouchMode = true

// Group related elements
group.isScreenReaderFocusable = true
group.contentDescription = "Cow details: ID 12345, Breed Holstein"

// Support switch selection
switchAccessibleView.setOnClickListener {
    // Handle activation
}
```

#### Switch Access Features

| Feature | Implementation |
|---------|---------------|
| Scanning | Auto-scan or step-scan |
| Group selection | First select group, then item |
| Action menu | Multiple actions per element |
| Settings | Adjustable scan speed |

### 16.2 iOS Switch Control

Support iOS Switch Control for external switches.

#### Implementation

```swift
// Ensure proper element grouping
container.isAccessibilityElement = true
container.accessibilityLabel = "Cow details"

// Support switch navigation
button.isAccessibilityElement = true
button.accessibilityTraits = .button

// Custom actions for switches
container.accessibilityCustomActions = [
    UIAccessibilityCustomAction(
        name: "Edit",
        target: self,
        selector: #selector(editAction)
    ),
    UIAccessibilityCustomAction(
        name: "Delete",
        target: self,
        selector: #selector(deleteAction)
    )
]
```

---

## 17. Documentation

### 17.1 Accessibility Statement

Smart Dairy Ltd. provides the following accessibility statement:

---

**Smart Dairy Mobile App Accessibility Statement**

Smart Dairy Ltd. is committed to ensuring digital accessibility for people with disabilities. We are continually improving the user experience for everyone and applying the relevant accessibility standards.

**Conformance Status**

The Smart Dairy mobile application aims to conform to WCAG 2.1 Level AA standards.

**Accessibility Features**
- Full screen reader support (TalkBack, VoiceOver)
- Adjustable text sizes
- High contrast mode
- Voice input support
- Switch access compatibility
- Reduced motion support

**Compatibility**
- Android 8.0+ with TalkBack
- iOS 14+ with VoiceOver
- Compatible with major switch access devices

**Known Limitations**
- Some third-party integrations may have accessibility limitations
- Custom charts may require additional description

**Feedback**
We welcome your feedback on the accessibility of Smart Dairy. Please contact us at:
- Email: accessibility@smartdairy.com
- Phone: +1-800-SMART-DAIRY

**Date**: January 31, 2026

---

### 17.2 Developer Documentation

All accessibility implementations must be documented:

- Code comments for accessibility features
- Accessibility test cases
- User documentation for accessibility features
- Training materials for support staff

---

## 18. Appendices

### Appendix A: Accessibility Testing Checklist

#### Screen Reader Testing

| # | Test Item | Pass | Fail | Notes |
|---|-----------|------|------|-------|
| 1 | All images have alt text | ☐ | ☐ | |
| 2 | All buttons have labels | ☐ | ☐ | |
| 3 | All form fields have labels | ☐ | ☐ | |
| 4 | Navigation is logical | ☐ | ☐ | |
| 5 | Focus is visible | ☐ | ☐ | |
| 6 | Dynamic updates announced | ☐ | ☐ | |
| 7 | Error messages announced | ☐ | ☐ | |
| 8 | Screen changes announced | ☐ | ☐ | |
| 9 | Headings properly marked | ☐ | ☐ | |
| 10 | Lists properly structured | ☐ | ☐ | |

#### Visual Testing

| # | Test Item | Pass | Fail | Notes |
|---|-----------|------|------|-------|
| 1 | Contrast ratio 4.5:1 for text | ☐ | ☐ | |
| 2 | Contrast ratio 3:1 for UI | ☐ | ☐ | |
| 3 | Color not sole indicator | ☐ | ☐ | |
| 4 | Text scales to 200% | ☐ | ☐ | |
| 5 | Layout works at 200% zoom | ☐ | ☐ | |
| 6 | Focus indicators visible | ☐ | ☐ | |

#### Touch/Input Testing

| # | Test Item | Pass | Fail | Notes |
|---|-----------|------|------|-------|
| 1 | Touch targets 48x48dp minimum | ☐ | ☐ | |
| 2 | Touch targets spaced properly | ☐ | ☐ | |
| 3 | No precision gestures required | ☐ | ☐ | |
| 4 | Input fields have correct keyboard | ☐ | ☐ | |
| 5 | Voice input works | ☐ | ☐ | |
| 6 | Switch access works | ☐ | ☐ | |

#### Farm Worker Specific Testing

| # | Test Item | Pass | Fail | Notes |
|---|-----------|------|------|-------|
| 1 | Large touch targets (56dp) | ☐ | ☐ | |
| 2 | High contrast mode available | ☐ | ☐ | |
| 3 | Audio feedback for confirmations | ☐ | ☐ | |
| 4 | Simple, consistent layouts | ☐ | ☐ | |
| 5 | Works with gloves | ☐ | ☐ | |
| 6 | Visible in direct sunlight | ☐ | ☐ | |

### Appendix B: Code Examples

#### Complete Accessible Button (Android)

```kotlin
class AccessibleButton @JvmOverloads constructor(
    context: Context,
    attrs: AttributeSet? = null,
    defStyleAttr: Int = 0
) : MaterialButton(context, attrs, defStyleAttr) {
    
    init {
        // Minimum touch target
        minHeight = 56.dpToPx()
        minWidth = 56.dpToPx()
        
        // Visual feedback
        isHapticFeedbackEnabled = true
        
        // Ensure proper contrast
        setTextColor(context.getColor(R.color.accessible_text))
    }
    
    override fun performClick(): Boolean {
        // Haptic feedback
        performHapticFeedback(HapticFeedbackConstants.CONFIRM)
        return super.performClick()
    }
}
```

#### Complete Accessible Form Field (Android)

```kotlin
class AccessibleTextInputLayout @JvmOverloads constructor(
    context: Context,
    attrs: AttributeSet? = null
) : TextInputLayout(context, attrs) {
    
    init {
        // Style
        boxBackgroundMode = BOX_BACKGROUND_OUTLINE
        
        // Accessibility
        importantForAccessibility = View.IMPORTANT_FOR_ACCESSIBILITY_YES
    }
    
    fun setError(message: String?) {
        super.setError(message)
        
        // Announce error
        if (message != null) {
            editText?.announceForAccessibility("Error: $message")
        }
    }
}
```

#### Accessible List Item (iOS)

```swift
class AccessibleCowCell: UITableViewCell {
    
    private let nameLabel = UILabel()
    private let idLabel = UILabel()
    private let statusView = UIView()
    
    override init(style: UITableViewCell.CellStyle, reuseIdentifier: String?) {
        super.init(style: style, reuseIdentifier: reuseIdentifier)
        setupAccessibility()
    }
    
    private func setupAccessibility() {
        // Group cell contents
        isAccessibilityElement = true
        
        // Combine labels
        accessibilityLabel = "\(nameLabel.text ?? ""), ID: \(idLabel.text ?? \"\")"
        
        // Add traits
        accessibilityTraits = .button
        
        // Add hint
        accessibilityHint = "Double-tap to view cow details"
    }
    
    func configure(with cow: Cow) {
        nameLabel.text = cow.name
        idLabel.text = cow.id
        
        // Update accessibility
        accessibilityLabel = "\(cow.name), ID: \(cow.id), Status: \(cow.status)"
        
        // Custom action
        accessibilityCustomActions = [
            UIAccessibilityCustomAction(
                name: "Edit Cow",
                target: self,
                selector: #selector(editCow)
            )
        ]
    }
    
    @objc private func editCow() -> Bool {
        // Handle edit action
        return true
    }
}
```

#### Accessibility Utility Functions

```kotlin
// Android Accessibility Utilities
object AccessibilityUtils {
    
    // Announce to screen reader
    fun announce(view: View, message: String) {
        view.announceForAccessibility(message)
    }
    
    // Check if screen reader is active
    fun isScreenReaderActive(context: Context): Boolean {
        val am = context.getSystemService(Context.ACCESSIBILITY_SERVICE) as AccessibilityManager
        return am.isEnabled && am.isTouchExplorationEnabled
    }
    
    // Set as heading
    fun setHeading(view: View) {
        ViewCompat.setAccessibilityHeading(view, true)
    }
    
    // Hide from screen reader
    fun hideFromScreenReader(view: View) {
        view.importantForAccessibility = View.IMPORTANT_FOR_ACCESSIBILITY_NO
    }
    
    // Set traversal order
    fun setTraversalOrder(before: View, after: View) {
        ViewCompat.setAccessibilityTraversalBefore(after, before.id)
    }
}
```

```swift
// iOS Accessibility Utilities
struct AccessibilityUtils {
    
    // Announce to VoiceOver
    static func announce(_ message: String) {
        UIAccessibility.post(notification: .announcement, argument: message)
    }
    
    // Check if VoiceOver is running
    static var isVoiceOverRunning: Bool {
        return UIAccessibility.isVoiceOverRunning
    }
    
    // Check if Reduce Motion is enabled
    static var isReduceMotionEnabled: Bool {
        return UIAccessibility.isReduceMotionEnabled
    }
    
    // Notify screen changed
    static func notifyScreenChanged(element: Any?) {
        UIAccessibility.post(notification: .screenChanged, argument: element)
    }
    
    // Notify layout changed
    static func notifyLayoutChanged(element: Any?) {
        UIAccessibility.post(notification: .layoutChanged, argument: element)
    }
}
```

### Appendix C: Resources

#### Guidelines and Standards
- [WCAG 2.1](https://www.w3.org/WAI/WCAG21/quickref/)
- [Android Accessibility Guidelines](https://developer.android.com/guide/topics/ui/accessibility)
- [iOS Accessibility Guidelines](https://developer.apple.com/accessibility/)

#### Testing Tools
- Android Accessibility Scanner
- iOS Accessibility Inspector
- axe DevTools
- Lighthouse

#### Training Resources
- WebAIM Accessibility Training
- Google's Accessibility Development Resources
- Apple's Accessibility Programming Guide

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | UI/UX Lead | _________________ | _______ |
| Owner | Mobile Lead | _________________ | _______ |
| Reviewer | QA Lead | _________________ | _______ |
| Approver | CTO | _________________ | _______ |

---

## Distribution List

| Role | Name | Department |
|------|------|------------|
| Mobile Development Team | All Members | Engineering |
| QA Team | All Members | Quality Assurance |
| UI/UX Team | All Members | Design |
| Product Management | Product Manager | Product |

---

*End of Document H-018: Mobile Accessibility Guide*

**Smart Dairy Ltd.**  
*Innovating Dairy Farm Management*
